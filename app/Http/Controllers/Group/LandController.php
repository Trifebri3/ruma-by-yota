<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();
        $lands = $group ? $group->lands()->latest()->get() : collect();

        $totalAreaM2 = $lands->sum('area_m2');
        $totalAreaHa = round($totalAreaM2 / 10000, 2);

        return view('group.land.index', compact('user', 'group', 'lands', 'totalAreaM2', 'totalAreaHa'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();

        if (! $group) {
            return redirect()->back()->with('error', 'Kelompok belum terhubung.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'land_status' => 'required|string',
            'area_m2' => 'required|numeric|min:1',
            'manager_name' => 'nullable|string|max:255',
            'commodities' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'polygon_geojson' => 'nullable|string',
            'is_public' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['group_id'] = $group->id;
        $validated['is_public'] = $request->boolean('is_public', true);
        $validated['latitude'] = $validated['latitude'] ?? -7.351234;
        $validated['longitude'] = $validated['longitude'] ?? 108.152345;
        $validated['photo_url'] = 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=800&q=80';

        Land::create($validated);

        return redirect()->route('group.land.index')->with('success', 'Data lahan berhasil dicatat dengan koordinat peta.');
    }
}
