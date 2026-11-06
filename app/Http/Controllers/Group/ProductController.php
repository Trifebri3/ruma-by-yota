<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();
        $products = $group ? $group->products()->with('commodity')->latest()->get() : collect();
        $commodities = $group ? $group->commodities : collect();

        return view('group.products.index', compact('user', 'group', 'products', 'commodities'));
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
            'commodity_id' => 'nullable|exists:commodities,id',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'packaging_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_public' => 'nullable|boolean',
        ]);

        $validated['group_id'] = $group->id;
        $validated['is_public'] = $request->boolean('is_public', true);
        $validated['status'] = 'dalam_evaluasi';
        $validated['qc_label_status'] = 'belum_dinilai';
        $validated['qc_packaging_status'] = 'belum_dinilai';
        $validated['qc_info_status'] = 'belum_dinilai';
        $validated['qc_photo_status'] = 'belum_dinilai';
        $validated['photo_url'] = 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80';

        Product::create($validated);

        return redirect()->route('group.products.index')->with('success', 'Produk berhasil didaftarkan dan masuk evaluasi QC Fasilitator.');
    }
}
