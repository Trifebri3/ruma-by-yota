<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Group;

class BusinessController extends Controller
{
    public function show($slug)
    {
        $group = Group::where('slug', $slug)
            ->where('status', 'active')
            ->where('is_public', true)
            ->with(['products' => function ($q) {
                $q->where('is_public', true);
            }, 'commodities'])
            ->firstOrFail();

        return view('public.businesses.show', compact('group'));
    }
}
