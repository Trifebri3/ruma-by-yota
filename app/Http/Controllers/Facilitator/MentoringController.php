<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\MentoringNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentoringController extends Controller
{
    public function index()
    {
        $facilitator = Auth::user();
        $notes = MentoringNote::with('group')
            ->where('facilitator_id', $facilitator->id)
            ->latest('date')
            ->get();
        $groups = $facilitator->assignedGroups ?? Group::all();

        return view('facilitator.mentoring.index', compact('notes', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'topic' => 'required|string|max:255',
            'observations' => 'required|string',
            'action_items' => 'nullable|string',
            'date' => 'required|date',
        ]);

        MentoringNote::create([
            'group_id' => $request->group_id,
            'facilitator_id' => Auth::id(),
            'date' => $request->date,
            'topic' => $request->topic,
            'observations' => $request->observations,
            'action_items' => $request->action_items,
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Catatan pendampingan lapangan berhasil disimpan.');
    }
}
