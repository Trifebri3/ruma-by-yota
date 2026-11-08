<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect(Auth::user()->home_route);
        }

        return view('auth.login');
    }

    /**
     * Proses Login Email & Password
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan oleh Administrator. Silakan hubungi pihak SiYota.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            session(['active_demo_user_id' => $user->id]);

            $roleName = $user->clean_role_label;

            return redirect()->intended($user->home_route)->with('success', "Selamat datang kembali, {$user->name}! Masuk sebagai {$roleName}.");
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Shortcut Login Cepat Berdasarkan 3 Role
     */
    public function quickRoleLogin($role)
    {
        $targetEmail = match (strtolower($role)) {
            'user', 'kelompok' => 'siti@ruma.siyota.org',
            'fasil', 'facilitator' => 'rian@siyota.org',
            'admin', 'super_admin' => 'admin@siyota.org',
            default => 'siti@ruma.siyota.org',
        };

        $user = User::where('email', $targetEmail)->first();
        if ($user) {
            Auth::login($user);
            session(['active_demo_user_id' => $user->id]);

            return redirect($user->home_route)->with('success', "Berhasil masuk sebagai {$user->name} ({$user->clean_role_label}).");
        }

        return redirect()->route('login')->with('error', 'Akun tidak ditemukan.');
    }

    /**
     * Tampilkan Halaman Pendaftaran (Register Akun)
     */
    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect(Auth::user()->home_route);
        }

        $selectedRole = $request->query('role', 'user');

        return view('auth.register', compact('selectedRole'));
    }

    /**
     * Proses Pendaftaran Akun Baru (3 Role: USER, FASIL, ADMIN)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:user,fasil,admin',
            'phone' => 'nullable|string|max:50',
            'group_name' => 'nullable|string|max:255',
        ]);

        $role = $request->input('role', 'user');
        $groupId = null;
        $positionTitle = match ($role) {
            'admin' => 'Administrator SiYota',
            'fasil' => 'Fasilitator Pendamping Lapangan',
            default => 'Anggota Kelompok',
        };

        if ($role === 'user') {
            // Attach to default group or create group if provided
            if ($request->filled('group_name')) {
                $group = Group::create([
                    'name' => $request->group_name,
                    'slug' => Str::slug($request->group_name).'-'.Str::random(4),
                    'category' => 'Kelompok Mandiri',
                    'regency' => 'Jawa Barat',
                    'leader_name' => $request->name,
                    'contact_phone' => $request->phone ?? '0812-0000-0000',
                    'status' => 'active',
                ]);
                $groupId = $group->id;
                $positionTitle = 'Ketua Kelompok';
            } else {
                $defaultGroup = Group::first();
                $groupId = $defaultGroup?->id;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'position_title' => $positionTitle,
            'phone' => $request->phone,
            'group_id' => $groupId,
            'avatar_url' => 'https://ui-avatars.com/api/?name='.urlencode($request->name).'&background=B88E3E&color=fff',
        ]);

        Auth::login($user);
        session(['active_demo_user_id' => $user->id]);

        return redirect($user->home_route)->with('success', "Pendaftaran berhasil! Selamat datang, {$user->name} ({$user->clean_role_label}).");
    }

    /**
     * Redirect ke Google OAuth
     */
    public function redirectToGoogle(Request $request)
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->with('error', 'Konfigurasi Google OAuth belum diisi di file .env (GOOGLE_CLIENT_ID & GOOGLE_CLIENT_SECRET).');
        }

        try {
            return Socialite::driver('google')->redirect();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal mengarahkan ke Google: '.$e->getMessage());
        }
    }

    /**
     * Handle Google Callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $googleId = $googleUser->getId();
            $email = $googleUser->getEmail();
            $name = $googleUser->getName();
            $avatar = $googleUser->getAvatar();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal menghubungkan akun Google: '.$e->getMessage());
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleId)->orWhere('email', $email)->first();

        if (! $user) {
            // Default role SELALU 'user' (Kelompok Tani / Usaha)
            $defaultGroup = Group::first();

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(16)),
                'role' => 'user',
                'position_title' => 'Anggota Kelompok',
                'group_id' => $defaultGroup?->id,
                'google_id' => $googleId,
                'avatar_url' => $avatar,
                'email_verified_at' => now(),
            ]);
        } else {
            // Update google_id & avatar jika belum ada
            if (! $user->google_id) {
                $user->update([
                    'google_id' => $googleId,
                    'avatar_url' => $user->avatar_url ?: $avatar,
                ]);
            }
        }

        Auth::login($user, true);

        return redirect($user->home_route)->with('success', "Selamat datang, {$user->name}! Berhasil masuk dengan akun Google.");
    }

    /**
     * Logout Pengguna
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar (logout).');
    }
}
