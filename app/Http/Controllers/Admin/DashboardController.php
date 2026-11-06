<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Land;
use App\Models\MarketingSubmission;
use App\Models\NotificationLog;
use App\Models\Product;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalGroups = Group::count();
        $activeGroups = Group::where('status', 'active')->count();
        $pendingGroups = Group::where('status', 'pending')->count();
        $facilitatorsCount = User::whereIn('role', ['fasil', 'facilitator'])->count();
        $totalLands = Land::count();
        $totalProducts = Product::count();
        $pendingReviews = MarketingSubmission::where('status', 'dikumpulkan')->count();
        $recentNotifications = NotificationLog::with('group')->latest()->take(5)->get();
        $recentPending = Group::where('status', 'pending')->latest()->take(3)->get();
        $programs = Program::withCount('groups')->get();

        return view('admin.dashboard', compact(
            'totalGroups',
            'activeGroups',
            'pendingGroups',
            'facilitatorsCount',
            'totalLands',
            'totalProducts',
            'pendingReviews',
            'recentNotifications',
            'recentPending',
            'programs'
        ));
    }
}
