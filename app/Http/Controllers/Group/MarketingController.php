<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\MarketingSubmission;
use App\Models\MarketingTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();
        $tab = $request->query('tab', 'semua');

        $tasks = MarketingTask::where('is_active', true)->orderBy('deadline')->get();

        $submissions = $group ? MarketingSubmission::where('group_id', $group->id)->get()->keyBy('task_id') : collect();

        // Calculate counts for tabs
        $counts = [
            'semua' => $tasks->count(),
            'belum' => 0,
            'review' => 0,
            'revisi' => 0,
            'disetujui' => 0,
        ];

        // Organize into two distinct Wadah: Belum Selesai (Pending/Revisi) vs Sudah Selesai (Approved)
        $pendingTasks = collect();
        $completedTasks = collect();

        foreach ($tasks as $task) {
            $sub = $submissions->get($task->id);
            $task->current_submission = $sub;

            if (! $sub || $sub->status === 'belum_dikerjakan') {
                $counts['belum']++;
                $pendingTasks->push($task);
            } elseif ($sub->status === 'dikumpulkan') {
                $counts['review']++;
                $pendingTasks->push($task);
            } elseif ($sub->status === 'perlu_revisi') {
                $counts['revisi']++;
                $pendingTasks->push($task);
            } elseif (in_array($sub->status, ['disetujui', 'dipublikasikan'])) {
                $counts['disetujui']++;
                $completedTasks->push($task);
            }
        }

        return view('group.marketing.index', compact('user', 'group', 'tasks', 'pendingTasks', 'completedTasks', 'submissions', 'tab', 'counts'));
    }

    public function submit(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();

        if (! $group) {
            return redirect()->back()->with('error', 'Kelompok belum terhubung.');
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:marketing_tasks,id',
            'caption' => 'nullable|string',
            'notes' => 'nullable|string',
            'photo_url' => 'nullable|url',
        ]);

        $photoUrl = $validated['photo_url'] ?? 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80';

        MarketingSubmission::updateOrCreate(
            [
                'task_id' => $validated['task_id'],
                'group_id' => $group->id,
            ],
            [
                'photo_url' => $photoUrl,
                'caption' => $validated['caption'] ?? '',
                'notes' => $validated['notes'] ?? '',
                'status' => 'dikumpulkan',
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('group.marketing.index')->with('success', 'Tugas marketing berhasil dikirim untuk di-review fasilitator.');
    }
}
