<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Group;

class GroupController extends Controller
{
    public function show($slug)
    {
        $group = Group::where('slug', $slug)
            ->where('status', 'active')
            ->where('is_public', true)
            ->with([
                'commodities',
                'products' => function ($q) {
                    $q->where('is_public', true);
                },
                'lands' => function ($q) {
                    $q->where('is_public', true);
                },
            ])
            ->firstOrFail();

        return view('public.groups.show', compact('group'));
    }
}
