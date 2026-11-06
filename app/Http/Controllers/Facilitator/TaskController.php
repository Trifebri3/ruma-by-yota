<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Models\MarketingTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = MarketingTask::with('facilitator')->latest()->get();

        return view('facilitator.tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:normal,tinggi',
            'reference_pdf_name' => 'nullable|string',
            'reference_image_url' => 'nullable|string',
        ]);

        MarketingTask::create([
            'facilitator_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->input('instructions', [
                'Ikuti petunjuk teknis pencahayaan dan komposisi.',
                'Ambil foto produk dan proses kerja lapangan.',
                'Buat keterangan teks yang jelas dan cantumkan kontak pemesanan.',
            ]),
            'reference_pdf_name' => $request->reference_pdf_name ?? 'Panduan Standar SiYota.pdf',
            'reference_image_url' => $request->reference_image_url,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'is_active' => true,
        ]);

        return back()->with('success', 'Tugas pemasaran baru berhasil dikirimkan ke kelompok.');
    }
}
