<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Land;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Public only displays approved & public groups
        $query = Group::where('status', 'active')
            ->where('is_public', true)
            ->with(['commodities', 'products' => function ($q) {
                $q->where('is_public', true);
            }])
            ->latest();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('regency', 'like', "%{$keyword}%")
                    ->orWhere('commodity_summary', 'like', "%{$keyword}%")
                    ->orWhere('business_name', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('regency')) {
            $query->where('regency', $request->regency);
        }

        $groups = $query->get();
        $regencies = Group::where('status', 'active')->where('is_public', true)->pluck('regency')->unique();

        $publicLands = Land::where('is_public', true)->with('group')->get();
        $publicProducts = Product::where('is_public', true)->with('group')->take(6)->get();

        return view('public.home', compact('groups', 'publicLands', 'publicProducts', 'regencies'));
    }
}
