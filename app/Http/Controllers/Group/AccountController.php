<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();

        // Internal group members (leader, treasurer, product manager, etc.)
        $members = $group ? User::where('group_id', $group->id)->get() : collect();

        // If only 1 user exists for group, prepare default display members
        $defaultMembers = [
            ['name' => 'Ibu Siti', 'role' => 'Ketua Kelompok & Admin Kelompok', 'phone' => '0812-3456-7890', 'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80'],
            ['name' => 'Ibu Rina', 'role' => 'Pengelola Keuangan', 'phone' => '0813-9876-5432', 'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80'],
            ['name' => 'Ibu Dewi', 'role' => 'Pengelola Produk & QC', 'phone' => '0815-5555-1234', 'avatar' => 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=300&q=80'],
        ];

        return view('group.account.index', compact('user', 'group', 'members', 'defaultMembers'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();

        if (! $group) {
            return redirect()->back()->with('error', 'Kelompok belum terhubung.');
        }

        $validated = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'public_description' => 'nullable|string|max:1000',
            'pic_name' => 'nullable|string|max:255',
            'pic_public_visible' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
            'contact_phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['pic_public_visible'] = $request->boolean('pic_public_visible', true);
        $validated['is_public'] = $request->boolean('is_public', true);

        $group->update($validated);

        return redirect()->route('group.account.index')->with('success', 'Profil dan pengaturan visibilitas kelompok berhasil diperbarui.');
    }
}
