<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('auth.register_group');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'village' => 'nullable|string|max:255',
            'commodity_summary' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name);
        if (Group::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::random(5);
        }

        $group = Group::create([
            'name' => $request->name,
            'slug' => $slug,
            'category' => 'Kelompok Pendaftar Baru',
            'regency' => $request->regency,
            'district' => $request->district,
            'village' => $request->village,
            'commodity_summary' => $request->commodity_summary,
            'status' => 'pending', // Menunggu verifikasi
            'leader_name' => $request->leader_name,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
            'verification_notes' => $request->notes ?? 'Pendaftaran online melalui formulir publik RUMA SiYota.',
        ]);

        return redirect()->route('register.success', ['group_id' => $group->id]);
    }

    public function success(Request $request)
    {
        $group = Group::find($request->query('group_id'));

        return view('auth.register_success', compact('group'));
    }
}
