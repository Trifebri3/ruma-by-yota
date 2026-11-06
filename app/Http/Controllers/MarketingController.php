<?php

namespace App\Http\Controllers;

use App\Models\MarketingSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:marketing_tasks,id',
            'group_id' => 'required|exists:groups,id',
            'caption' => 'required|string',
            'notes' => 'nullable|string',
            'photo_url' => 'nullable|string',
        ]);

        $defaultPhoto = 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=800&q=80';

        $submission = MarketingSubmission::updateOrCreate(
            [
                'task_id' => $request->task_id,
                'group_id' => $request->group_id,
            ],
            [
                'photo_url' => $request->photo_url ?: $defaultPhoto,
                'caption' => $request->caption,
                'notes' => $request->notes,
                'status' => 'dikumpulkan',
                'submitted_at' => Carbon::now(),
            ]
        );

        return back()->with('success', 'Konten berhasil dikumpulkan! Status kini "Menunggu Review" dari Fasilitator SiYota.');
    }
}
