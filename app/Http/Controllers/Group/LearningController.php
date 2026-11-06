<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\DocumentReadLog;
use App\Models\Group;
use App\Models\LearningClass;
use App\Models\LearningMaterial;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();
        $tab = $request->query('tab', 'dokumen'); // default to dokumen / SOP tab
        $docStatus = $request->query('doc_status', 'all'); // all, belum, sudah

        // 1. Kelas (Pelatihan & program belajar)
        $classes = LearningClass::where('is_published', true)->get();

        // 2. Materi (PDF, video, panduan)
        $materials = LearningMaterial::whereIn('file_type', ['pdf', 'video'])->get();

        // 3. Ruangan (Zoom meeting & agenda mentoring)
        $schedules = Schedule::where(function ($q) use ($group) {
            $q->whereNull('group_id');
            if ($group) {
                $q->orWhere('group_id', $group->id);
            }
        })->orderBy('event_date')->get();
        $mentoringNotes = $group ? $group->mentoringNotes()->with('facilitator')->latest()->get() : collect();

        // 4. Dokumen & SOP dengan tracking dibuka/belum
        $documents = LearningMaterial::whereIn('category', ['sop_produksi', 'quality_control', 'branding', 'kemasan', 'administrasi', 'keuangan'])->get();

        $readLogs = $group ? DocumentReadLog::where('group_id', $group->id)->get()->keyBy('material_id') : collect();

        $unopenedDocuments = collect();
        $openedDocuments = collect();

        foreach ($documents as $doc) {
            if ($readLogs->has($doc->id)) {
                $doc->read_log = $readLogs->get($doc->id);
                $openedDocuments->push($doc);
            } else {
                $unopenedDocuments->push($doc);
            }
        }

        return view('group.learning.index', compact(
            'user',
            'group',
            'tab',
            'docStatus',
            'classes',
            'materials',
            'schedules',
            'mentoringNotes',
            'documents',
            'readLogs',
            'unopenedDocuments',
            'openedDocuments'
        ));
    }

    /**
     * Buka dan catat log pembacaan SOP / Dokumen oleh kelompok
     */
    public function openDocument(Request $request, $id)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();
        $material = LearningMaterial::findOrFail($id);

        if ($group) {
            $log = DocumentReadLog::where('material_id', $material->id)
                ->where('group_id', $group->id)
                ->first();

            if ($log) {
                $log->increment('open_count');
                $log->update([
                    'opened_at' => now(),
                    'user_id' => $user->id,
                    'reader_name' => $user->name,
                ]);
            } else {
                DocumentReadLog::create([
                    'material_id' => $material->id,
                    'group_id' => $group->id,
                    'user_id' => $user->id,
                    'opened_at' => now(),
                    'open_count' => 1,
                    'reader_name' => $user->name,
                ]);
            }

            $material->increment('download_count');
        }

        if ($material->file_url && filter_var($material->file_url, FILTER_VALIDATE_URL)) {
            return redirect($material->file_url);
        }

        return redirect()->route('group.learning.index', ['tab' => 'dokumen', 'doc_status' => 'sudah'])
            ->with('success', "Dokumen '{$material->title}' berhasil dibuka dan waktu baca tercatat.");
    }
}
