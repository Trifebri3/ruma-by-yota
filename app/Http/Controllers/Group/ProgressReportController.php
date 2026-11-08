<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\DailyProgressReport;
use App\Models\DailyReportLink;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProgressReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();

        if (! $group) {
            return redirect()->route('group.dashboard')->with('error', 'Akun Anda belum terhubung ke kelompok binaan.');
        }

        $today = Carbon::now('Asia/Jakarta');
        $todayDate = $today->toDateString();

        $reports = DailyProgressReport::where('group_id', $group->id)
            ->with(['link', 'user'])
            ->latest('report_date')
            ->latest('id')
            ->paginate(10);

        // Tautan laporan aktif untuk kelompok ini
        $links = DailyReportLink::where('group_id', $group->id)
            ->where('is_active', true)
            ->latest()
            ->get();

        $todayReportsCount = DailyProgressReport::where('group_id', $group->id)
            ->whereDate('report_date', $todayDate)
            ->count();

        return view('group.progress.index', compact('group', 'reports', 'links', 'today', 'todayDate', 'todayReportsCount'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $group = $user->group ?? Group::first();

        if (! $group) {
            return redirect()->back()->with('error', 'Akun Anda belum terhubung ke kelompok.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string|min:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'title.required' => 'Judul laporan aktivitas wajib diisi.',
            'description.required' => 'Deskripsi aktivitas wajib diisi.',
            'description.min' => 'Deskripsi kegiatan minimal 10 karakter.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 5 MB.',
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = 'report_'.time().'_'.Str::random(8).'.'.$photo->getClientOriginalExtension();
            $destinationPath = public_path('uploads/daily_reports');

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $photo->move($destinationPath, $filename);
            $photoUrl = '/uploads/daily_reports/'.$filename;
        }

        $todayDate = Carbon::now('Asia/Jakarta')->toDateString();

        DailyProgressReport::create([
            'daily_report_link_id' => null,
            'group_id' => $group->id,
            'user_id' => $user->id,
            'reporter_name' => $user->name,
            'report_date' => $todayDate,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'photo_url' => $photoUrl,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'submitted',
        ]);

        return redirect()->route('group.progress.index')->with('success', 'Laporan progress harian Anda berhasil disimpan!');
    }
}
