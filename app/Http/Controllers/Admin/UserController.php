<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('group')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();
        $groups = Group::orderBy('name')->get();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'group_admin_role' => User::whereIn('role', ['user', 'group_admin'])->count(),
            'group_member_role' => User::where('role', 'group_member')->count(),
            'fasil_role' => User::whereIn('role', ['fasil', 'facilitator'])->count(),
            'admin_role' => User::whereIn('role', ['admin', 'super_admin'])->count(),
        ];

        return view('admin.users.index', compact('users', 'groups', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,group_admin,group_member,fasil,facilitator,admin,super_admin',
            'group_id' => 'nullable|exists:groups,id',
            'phone' => 'nullable|string|max:20',
            'position_title' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Jika role admin dan group_id tidak perlu, izinkan null
        if (in_array($validated['role'], ['admin', 'super_admin']) && ! $request->filled('group_id')) {
            $validated['group_id'] = null;
        }

        $user = User::create($validated);

        // Jika role fasil dan ada group_id, otomatis set facilitator_id pada kelompok
        if (in_array($validated['role'], ['fasil', 'facilitator']) && ! empty($validated['group_id'])) {
            $group = Group::find($validated['group_id']);
            if ($group) {
                $group->facilitator_id = $user->id;
                $group->save();
            }
        }

        return redirect()->route('admin.users.index')->with('success', "Akun {$user->name} dengan peran " . strtoupper($user->role) . " berhasil dibuat!");
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:user,group_admin,group_member,fasil,facilitator,admin,super_admin',
            'group_id' => 'nullable|exists:groups,id',
            'phone' => 'nullable|string|max:20',
            'position_title' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        // Jika role fasil dan ada group_id, hubungkan ke kelompok
        if (in_array($validated['role'], ['fasil', 'facilitator']) && ! empty($validated['group_id'])) {
            $group = Group::find($validated['group_id']);
            if ($group) {
                $group->facilitator_id = $user->id;
                $group->save();
            }
        }

        return redirect()->route('admin.users.index')->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$statusText}.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "Akun {$user->name} berhasil dihapus.");
    }
}
