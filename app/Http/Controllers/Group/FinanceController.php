<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();

        $query = $group ? $group->financialRecords()->with('commodity')->latest('transaction_date') : FinancialRecord::whereRaw('1=0');

        if ($request->filled('type') && in_array($request->type, ['masuk', 'keluar'])) {
            $query->where('type', $request->type);
        }

        $records = $query->paginate(15);

        $totalIncome = $group ? $group->total_income : 0;
        $totalExpense = $group ? $group->total_expense : 0;
        $totalBalance = $group ? $group->total_balance : 0;
        $commodities = $group ? $group->commodities : collect();

        // Print/export view check
        if ($request->query('view') === 'print') {
            $allRecords = $group ? $group->financialRecords()->latest('transaction_date')->get() : collect();

            return view('group.finance.print', compact('group', 'allRecords', 'totalIncome', 'totalExpense', 'totalBalance'));
        }

        return view('group.finance.index', compact(
            'user',
            'group',
            'records',
            'totalIncome',
            'totalExpense',
            'totalBalance',
            'commodities'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();

        if (! $group) {
            return redirect()->back()->with('error', 'Kelompok belum terhubung.');
        }

        $validated = $request->validate([
            'type' => 'required|in:masuk,keluar',
            'amount' => 'required|numeric|min:100',
            'transaction_date' => 'required|date',
            'category' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'commodity_id' => 'nullable|exists:commodities,id',
        ]);

        $validated['group_id'] = $group->id;
        $validated['recorded_by_user_id'] = $user->id;

        FinancialRecord::create($validated);

        return redirect()->route('group.finance.index')->with('success', 'Catatan keuangan berhasil disimpan.');
    }
}
