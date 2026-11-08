<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyReportLink;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportLinkController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyReportLink::with(['group', 'creator'])->latest();

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('group', function ($g) use ($search) {
                        $g->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $links = $query->paginate(15)->withQueryString();
        $groups = Group::orderBy('name')->get();

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $stats = [
            'total_links' => DailyReportLink::count(),
            'active_links' => DailyReportLink::where('is_active', true)->count(),
            'inactive_links' => DailyReportLink::where('is_active', false)->count(),
        ];

        return view('admin.report_links.index', compact('links', 'groups', 'stats', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'daily_limit' => 'required|integer|min:0|max:100', // 0 = tidak dibatasi
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['token'] = Str::random(32);
        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->boolean('is_active', true);

        $link = DailyReportLink::create($validated);

        return redirect()->route('admin.report_links.index')->with('success', "Tautan laporan untuk {$link->group->name} berhasil dibuat!");
    }

    public function update(Request $request, $id)
    {
        $link = DailyReportLink::findOrFail($id);

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'daily_limit' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $link->update($validated);

        return redirect()->route('admin.report_links.index')->with('success', 'Pengaturan tautan laporan berhasil diperbarui.');
    }

    public function toggle($id)
    {
        $link = DailyReportLink::findOrFail($id);
        $link->is_active = ! $link->is_active;
        $link->save();

        $statusText = $link->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Tautan laporan {$link->title} berhasil {$statusText}.");
    }

    public function destroy($id)
    {
        $link = DailyReportLink::findOrFail($id);
        $title = $link->title;
        $link->delete();

        return redirect()->route('admin.report_links.index')->with('success', "Tautan laporan '{$title}' berhasil dihapus.");
    }
}

