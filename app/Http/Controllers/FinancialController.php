<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'type' => 'required|in:masuk,keluar',
            'amount' => 'required|numeric|min:1000',
            'transaction_date' => 'required|date',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'commodity_id' => 'nullable|exists:commodities,id',
        ]);

        $user = Auth::user();

        FinancialRecord::create([
            'group_id' => $request->group_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'category' => $request->category,
            'description' => $request->description,
            'commodity_id' => $request->commodity_id,
            'recorded_by_user_id' => $user?->id,
        ]);

        $typeLabel = $request->type === 'masuk' ? 'Uang Masuk (+Rp'.number_format($request->amount, 0, ',', '.').')' : 'Uang Keluar (-Rp'.number_format($request->amount, 0, ',', '.').')';

        return back()->with('success', 'Catatan keuangan berhasil disimpan: '.$typeLabel.'. Saldo kelompok diperbarui.');
    }
}
