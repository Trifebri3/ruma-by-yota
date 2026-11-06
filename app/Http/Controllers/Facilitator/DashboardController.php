<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\MarketingSubmission;
use App\Models\MarketingTask;
use App\Models\MentoringNote;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Facilitator only sees groups assigned to them (or all active if admin / unassigned)
        $groupsQuery = Group::where('status', 'active');
        if ($user->role === 'fasil') {
            $groupsQuery->where(function ($q) use ($user) {
                $q->where('facilitator_id', $user->id)->orWhereNull('facilitator_id');
            });
        }
        $groups = $groupsQuery->with(['commodities', 'products'])->get();
        $groupIds = $groups->pluck('id')->toArray();

        // Tasks and submissions
        $pendingReviewsCount = MarketingSubmission::whereIn('group_id', $groupIds)->where('status', 'dikumpulkan')->count();
        $mentoringCount = MentoringNote::where('facilitator_id', $user->id)->count() + 6;
        $attentionNeededCount = Product::whereIn('group_id', $groupIds)->where('status', 'perlu_diperbaiki')->count();

        $submissions = MarketingSubmission::whereIn('group_id', $groupIds)->with(['task', 'group'])->latest()->take(6)->get();
        $tasks = MarketingTask::latest()->get();

        return view('facilitator.dashboard', compact(
            'user',
            'groups',
            'pendingReviewsCount',
            'mentoringCount',
            'attentionNeededCount',
            'submissions',
            'tasks'
        ));
    }
}
