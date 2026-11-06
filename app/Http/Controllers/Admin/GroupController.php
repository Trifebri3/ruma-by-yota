<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = Group::with(['facilitator', 'program'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $groups = $query->get();
        $facilitators = User::whereIn('role', ['fasil', 'facilitator'])->get();
        $programs = Program::where('status', 'aktif')->get();

        return view('admin.groups.index', compact('groups', 'status', 'facilitators', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:30',
            'commodity_summary' => 'nullable|string|max:255',
            'facilitator_id' => 'nullable|exists:users,id',
            'program_id' => 'nullable|exists:programs,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(4);
        $validated['status'] = 'active';
        $validated['is_public'] = true;
        $validated['pic_name'] = $validated['leader_name'];
        $validated['banner_url'] = 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=800&q=80';

        Group::create($validated);

        return back()->with('success', 'Kelompok baru berhasil ditambahkan.');
    }

    public function assignFacilitator(Request $request, $id)
    {
        return $this->updateFacilitator($request, $id);
    }

    public function updateFacilitator(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $request->validate([
            'facilitator_id' => 'nullable|exists:users,id',
            'program_id' => 'nullable|exists:programs,id',
            'is_public' => 'nullable|boolean',
        ]);

        $group->update([
            'facilitator_id' => $request->facilitator_id,
            'program_id' => $request->program_id,
            'is_public' => $request->boolean('is_public', true),
        ]);

        return back()->with('success', "Data kelompok {$group->name} berhasil diperbarui.");
    }
}
