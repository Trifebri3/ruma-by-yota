<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Models\DocumentReadLog;
use App\Models\Group;
use App\Models\LearningMaterial;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::where('status', 'active')->with(['commodities', 'products'])->get();

        return view('facilitator.groups.index', compact('groups'));
    }

    public function show($id)
    {
        $group = Group::with([
            'members',
            'lands',
            'commodities.land',
            'products.commodity',
            'financialRecords.commodity',
            'marketingSubmissions.task',
            'mentoringNotes',
        ])->findOrFail($id);

        // Track SOP & Document reading compliance for this group
        $allMaterials = LearningMaterial::all();
        $readLogs = DocumentReadLog::where('group_id', $group->id)->get()->keyBy('material_id');

        $openedMaterials = $allMaterials->filter(fn ($m) => $readLogs->has($m->id))->map(function ($m) use ($readLogs) {
            $m->read_log = $readLogs->get($m->id);

            return $m;
        });

        $unopenedMaterials = $allMaterials->reject(fn ($m) => $readLogs->has($m->id));

        return view('facilitator.groups.show', compact('group', 'openedMaterials', 'unopenedMaterials'));
    }
}
