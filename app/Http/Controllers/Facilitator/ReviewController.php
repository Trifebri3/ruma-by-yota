<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Models\MarketingSubmission;
use App\Models\NotificationLog;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $submissions = MarketingSubmission::with(['task', 'group'])->latest()->get();
        $products = Product::with(['group', 'commodity'])->latest()->get();

        return view('facilitator.reviews.index', compact('submissions', 'products'));
    }

    public function reviewSubmission(Request $request, $id)
    {
        $submission = MarketingSubmission::with('group')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:disetujui,perlu_revisi,dipublikasikan',
            'facilitator_feedback' => 'required|string',
        ]);

        $submission->update([
            'status' => $request->status,
            'facilitator_feedback' => $request->facilitator_feedback,
            'reviewed_at' => Carbon::now(),
        ]);

        // Send WhatsApp log to group leader
        NotificationLog::create([
            'group_id' => $submission->group_id,
            'channel' => 'whatsapp',
            'event_type' => 'review_konten',
            'recipient' => $submission->group->contact_phone,
            'message_preview' => "Fasilitator SiYota telah meninjau tugas {$submission->task->title}. Status: ".ucfirst($request->status).". Pesan: {$request->facilitator_feedback}",
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Hasil review dan catatan koreksi berhasil dikirim ke kelompok.');
    }

    public function evaluateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'status' => 'required|in:perlu_diperbaiki,dalam_evaluasi,sesuai_panduan',
            'qc_label_status' => 'required|in:sesuai,perbaikan,belum_dinilai',
            'qc_packaging_status' => 'required|in:sesuai,perbaikan,belum_dinilai',
            'qc_info_status' => 'required|in:sesuai,perbaikan,belum_dinilai',
            'qc_photo_status' => 'required|in:sesuai,perbaikan,belum_dinilai',
            'facilitator_notes' => 'nullable|string',
        ]);

        $product->update($request->only([
            'status',
            'qc_label_status',
            'qc_packaging_status',
            'qc_info_status',
            'qc_photo_status',
            'facilitator_notes',
        ]));

        return back()->with('success', "Evaluasi QC produk {$product->name} berhasil diperbarui.");
    }
}
