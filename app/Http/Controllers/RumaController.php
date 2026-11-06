<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\LearningClass;
use App\Models\LearningMaterial;
use App\Models\MarketingSubmission;
use App\Models\MarketingTask;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RumaController extends Controller
{
    /**
     * Get or set the active demo user/persona
     */
    protected function getActiveUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }

        $userId = session('active_demo_user_id');
        if ($userId && $user = User::find($userId)) {
            Auth::login($user);

            return $user;
        }

        // Default to Ibu Siti (Ketua KWT Sumber Rejeki)
        $defaultUser = User::where('email', 'siti@ruma.siyota.org')->first() ?? User::first();
        if ($defaultUser) {
            Auth::login($defaultUser);
            session(['active_demo_user_id' => $defaultUser->id]);
        }

        return $defaultUser;
    }

    /**
     * Switch persona instantly
     */
    public function switchPersona(Request $request, $roleKey)
    {
        $targetEmail = match ($roleKey) {
            'ibu_siti', 'group_admin' => 'siti@ruma.siyota.org',
            'ibu_rina', 'finance' => 'rina@ruma.siyota.org',
            'ibu_dewi', 'product' => 'dewi@ruma.siyota.org',
            'kang_rian', 'facilitator' => 'rian@siyota.org',
            'admin_siyota', 'admin' => 'admin@siyota.org',
            default => 'siti@ruma.siyota.org',
        };

        $user = User::where('email', $targetEmail)->first();
        if ($user) {
            Auth::login($user);
            session(['active_demo_user_id' => $user->id]);
        }

        if (in_array($roleKey, ['kang_rian', 'facilitator', 'admin_siyota', 'admin'])) {
            return redirect()->route('facilitator.dashboard')->with('success', 'Beralih ke tampilan '.$user->name.' ('.$user->position_title.')');
        }

        return redirect()->route('ruma.dashboard')->with('success', 'Beralih ke tampilan '.$user->name.' ('.$user->position_title.')');
    }

    /**
     * Group Dashboard (Beranda)
     */
    public function dashboard(Request $request)
    {
        $user = $this->getActiveUser();
        $group = $user->group ?? Group::where('slug', 'kwt-sumber-rejeki')->first() ?? Group::first();

        // 3 Pertanyaan Kunci Dashboard:
        // 1. Apa yang harus saya kerjakan? (Next Task)
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
            // Revision from marketing
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
                    'link' => route('ruma.marketing'),
                ];
            }

            // Revision from products
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
                    'link' => route('ruma.usaha', ['tab' => 'produk']),
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

        return view('ruma.dashboard', compact(
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

    /**
     * Belajar (Learning & Mentoring)
     */
    public function belajar(Request $request)
    {
        $user = $this->getActiveUser();
        $group = $user->group ?? Group::first();

        $tab = $request->query('tab', 'kelas'); // kelas, jadwal, materi, mentoring
        $classes = LearningClass::where('is_published', true)->get();
        $schedules = Schedule::orderBy('event_date')->get();

        $materialCategory = $request->query('category', 'all');
        $materialsQuery = LearningMaterial::query();
        if ($materialCategory !== 'all') {
            $materialsQuery->where('category', $materialCategory);
        }
        $materials = $materialsQuery->latest()->get();

        $mentoringNotes = $group ? $group->mentoringNotes()->with('facilitator')->latest()->get() : collect();

        return view('ruma.belajar', compact('user', 'group', 'tab', 'classes', 'schedules', 'materials', 'materialCategory', 'mentoringNotes'));
    }

    /**
     * Usaha (Resources, Lands, Commodities, Products, Finances)
     */
    public function usaha(Request $request)
    {
        $user = $this->getActiveUser();
        $group = $user->group ?? Group::first();

        $tab = $request->query('tab', 'keuangan'); // keuangan, lahan, komoditas, produk
        $lands = $group ? $group->lands : collect();
        $commodities = $group ? $group->commodities()->with('land')->get() : collect();
        $products = $group ? $group->products()->with('commodity')->get() : collect();
        $transactions = $group ? $group->financialRecords()->with(['commodity', 'recordedBy'])->latest('transaction_date')->latest('id')->get() : collect();

        $totalBalance = $group ? $group->total_balance : 0;
        $totalIncome = $group ? $group->total_income : 0;
        $totalExpense = $group ? $group->total_expense : 0;

        return view('ruma.usaha', compact('user', 'group', 'tab', 'lands', 'commodities', 'products', 'transactions', 'totalBalance', 'totalIncome', 'totalExpense'));
    }

    /**
     * Marketing Hub
     */
    public function marketing(Request $request)
    {
        $user = $this->getActiveUser();
        $group = $user->group ?? Group::first();

        $tasks = MarketingTask::where('is_active', true)->with('facilitator')->latest()->get();
        $submissions = $group ? MarketingSubmission::where('group_id', $group->id)->with('task')->latest()->get()->keyBy('task_id') : collect();

        $selectedTaskId = $request->query('task_id', $tasks->first()?->id);
        $selectedTask = $tasks->firstWhere('id', $selectedTaskId) ?? $tasks->first();
        $currentSubmission = $selectedTask && $group ? MarketingSubmission::where('task_id', $selectedTask->id)->where('group_id', $group->id)->first() : null;

        return view('ruma.marketing', compact('user', 'group', 'tasks', 'submissions', 'selectedTask', 'currentSubmission'));
    }

    /**
     * Kelompok (Profile, Members, Management)
     */
    public function kelompok(Request $request)
    {
        $user = $this->getActiveUser();
        $group = $user->group ?? Group::first();
        $members = $group ? $group->members()->get() : collect();
        $facilitator = $group ? $group->facilitator : null;

        return view('ruma.kelompok', compact('user', 'group', 'members', 'facilitator'));
    }
}
