<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'packaging_type' => 'nullable|string|max:100',
            'commodity_id' => 'nullable|exists:commodities,id',
            'photo_url' => 'nullable|string',
        ]);

        $defaultPhoto = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80';

        Product::create([
            'group_id' => $request->group_id,
            'commodity_id' => $request->commodity_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'unit' => $request->unit,
            'packaging_type' => $request->packaging_type ?? 'Kemasan Standar',
            'photo_url' => $request->photo_url ?: $defaultPhoto,
            'status' => 'dalam_evaluasi',
            'qc_label_status' => 'belum_dinilai',
            'qc_packaging_status' => 'belum_dinilai',
            'qc_info_status' => 'belum_dinilai',
            'qc_photo_status' => 'belum_dinilai',
            'facilitator_notes' => 'Produk baru diinput oleh kelompok, menunggu evaluasi dari Fasilitator SiYota.',
        ]);

        return back()->with('success', 'Produk berhasil didaftarkan! Masuk antrean evaluasi QC oleh Fasilitator SiYota.');
    }
}
