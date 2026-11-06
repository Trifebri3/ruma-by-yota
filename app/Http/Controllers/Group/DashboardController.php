<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\MarketingSubmission;
use App\Models\MarketingTask;
use App\Models\Product;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::where('slug', 'kwt-sumber-rejeki')->first() ?? Group::first();

        // 1. Apa yang harus saya kerjakan?
        $activeTask = MarketingTask::where('is_active', true)->orderBy('deadline')->first();
        $mySubmission = null;
        if ($activeTask && $group) {
            $mySubmission = MarketingSubmission::where('task_id', $activeTask->id)
                ->where('group_id', $group->id)
                ->first();
        }

        // 2. Bagaimana perkembangan kelompok saya?
        $commoditiesCount = $group ? $group->commodities()->count() : 0;
        $commoditiesList = $group ? $group->commodities()->pluck('name')->implode(', ') : 'Cabai, aren, sayuran';
        $productsCount = $group ? $group->products()->count() : 0;
        $totalBalance = $group ? $group->total_balance : 0;
        $totalIncome = $group ? $group->total_income : 0;
        $totalExpense = $group ? $group->total_expense : 0;

        $totalTasks = MarketingTask::count();
        $completedTasks = $group ? MarketingSubmission::where('group_id', $group->id)->whereIn('status', ['disetujui', 'dipublikasikan'])->count() : 0;

        // 3. Apa yang perlu diperbaiki?
        $revisionItems = [];
        if ($group) {
            $pendingRevision = MarketingSubmission::where('group_id', $group->id)
                ->where('status', 'perlu_revisi')
                ->with('task')
                ->latest()
                ->first();
            if ($pendingRevision) {
                $revisionItems[] = [
                    'type' => 'Marketing',
                    'title' => $pendingRevision->task->title ?? 'Tugas Konten',
                    'note' => $pendingRevision->facilitator_feedback,
                    'badge' => 'Perlu Revisi',
                    'link' => route('group.marketing.index'),
                ];
            }

            $productFix = Product::where('group_id', $group->id)
                ->where('status', 'perlu_diperbaiki')
                ->latest()
                ->first();
            if ($productFix) {
                $revisionItems[] = [
                    'type' => 'Produk / QC',
                    'title' => $productFix->name,
                    'note' => $productFix->facilitator_notes,
                    'badge' => 'Perlu Perbaikan Kemasan/Info',
                    'link' => route('group.products.index'),
                ];
            }
        }

        // Schedules
        $upcomingSchedules = Schedule::where(function ($q) use ($group) {
            $q->whereNull('group_id');
            if ($group) {
                $q->orWhere('group_id', $group->id);
            }
        })
            ->where('event_date', '>=', now()->subDays(5))
            ->orderBy('event_date')
            ->take(3)
            ->get();

        // Recent Transactions
        $recentTransactions = $group ? $group->financialRecords()->latest('transaction_date')->take(3)->get() : collect();

        return view('group.dashboard.index', compact(
            'user',
            'group',
            'activeTask',
            'mySubmission',
            'commoditiesCount',
            'commoditiesList',
            'productsCount',
            'totalBalance',
            'totalIncome',
            'totalExpense',
            'totalTasks',
            'completedTasks',
            'revisionItems',
            'upcomingSchedules',
            'recentTransactions'
        ));
    }
}
