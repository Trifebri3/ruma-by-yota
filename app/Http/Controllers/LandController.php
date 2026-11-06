<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;

class LandController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'area_m2' => 'required|numeric|min:1',
            'land_status' => 'required|string|max:100',
            'manager_name' => 'nullable|string|max:255',
            'commodities' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $defaultPhoto = 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=800&q=80';

        Land::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'area_m2' => $request->area_m2,
            'land_status' => $request->land_status,
            'manager_name' => $request->manager_name,
            'commodities' => $request->commodities,
            'photo_url' => $defaultPhoto,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Titik lahan berhasil ditambahkan ke peta dan data kelompok!');
    }
}
