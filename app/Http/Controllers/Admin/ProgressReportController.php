<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyProgressReport;
use App\Models\DailyReportLink;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProgressReportController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyProgressReport::with(['group', 'link', 'user'])->latest();

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('report_date', $today);
                    break;
                case 'last_7_days':
                    $query->whereDate('report_date', '>=', Carbon::now('Asia/Jakarta')->subDays(7)->toDateString());
                    break;
                case 'last_30_days':
                    $query->whereDate('report_date', '>=', Carbon::now('Asia/Jakarta')->subDays(30)->toDateString());
                    break;
            }
        } elseif ($request->filled('date')) {
            $query->whereDate('report_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reporter_name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(15)->withQueryString();
        $groups = Group::orderBy('name')->get();

        $stats = [
            'total_reports' => DailyProgressReport::count(),
            'today_reports' => DailyProgressReport::whereDate('report_date', $today)->count(),
            'active_links' => DailyReportLink::where('is_active', true)->count(),
            'active_groups' => DailyProgressReport::distinct('group_id')->count('group_id'),
        ];

        return view('admin.reports.index', compact('reports', 'groups', 'stats', 'today'));
    }

    public function updateStatus(Request $request, $id)
    {
        $report = DailyProgressReport::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:submitted,reviewed,approved',
            'admin_notes' => 'nullable|string',
        ]);

        $report->update($validated);

        return back()->with('success', 'Status laporan progress berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $report = DailyProgressReport::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Laporan progress berhasil dihapus.');
    }
}

