<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Commodity;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommodityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();
        $commodities = $group ? $group->commodities()->with('land')->latest()->get() : collect();
        $lands = $group ? $group->lands : collect();

        return view('group.commodities.index', compact('user', 'group', 'commodities', 'lands'));
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
            'category' => 'required|string',
            'land_id' => 'nullable|exists:lands,id',
            'harvest_estimate' => 'nullable|string|max:255',
            'planted_area_m2' => 'nullable|numeric',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $validated['group_id'] = $group->id;
        $validated['photo_url'] = 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80';

        Commodity::create($validated);

        return redirect()->route('group.commodities.index')->with('success', 'Komoditas berhasil ditambahkan.');
    }
}
