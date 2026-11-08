<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DailyProgressReport;
use App\Models\DailyReportLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicReportController extends Controller
{
    /**
     * Tampilkan formulir pelaporan progres harian tanpa login
     */
    public function show($token)
    {
        $link = DailyReportLink::with('group')->where('token', $token)->firstOrFail();

        $today = Carbon::now('Asia/Jakarta');
        $todayDate = $today->toDateString();

        $isLinkActive = $link->isValidNow();
        $todayReportsCount = $link->todayReportsCount($todayDate);
        $isQuotaFull = $link->hasReachedDailyLimit($todayDate);
        $remainingQuota = $link->remainingDailyQuota($todayDate);

        // Ambil riwayat laporan hari ini untuk tautan ini (nama & waktu saja untuk transparansi kelompok)
        $todayReports = DailyProgressReport::where('daily_report_link_id', $link->id)
            ->whereDate('report_date', $todayDate)
            ->latest()
            ->get();

        return view('public.reports.submit', compact(
            'link',
            'today',
            'todayDate',
            'isLinkActive',
            'todayReportsCount',
            'isQuotaFull',
            'remainingQuota',
            'todayReports'
        ));
    }

    /**
     * Simpan laporan progres dari tautan publik
     */
    public function submit(Request $request, $token)
    {
        $link = DailyReportLink::with('group')->where('token', $token)->firstOrFail();

        $today = Carbon::now('Asia/Jakarta');
        $todayDate = $today->toDateString();

        // 1. Validasi Keaktifan Tautan & Periode
        if (! $link->isValidNow()) {
            return back()->with('error', 'Tautan laporan ini sedang tidak aktif atau periode pelaporan telah berakhir.')->withInput();
        }

        // 2. Validasi Kuota Harian di Backend
        if ($link->hasReachedDailyLimit($todayDate)) {
            $limitMsg = $link->daily_limit > 0 ? " (maksimal {$link->daily_limit} laporan/hari)" : '';

            return back()->with('error', "Kuota laporan hari ini{$limitMsg} sudah terpenuhi. Pengiriman laporan kembali tersedia besok (WIB).")->withInput();
        }

        // 3. Validasi Data Masukan
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:100',
            'title' => 'required|string|max:150',
            'description' => 'required|string|min:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Maks 5MB
        ], [
            'reporter_name.required' => 'Nama pelapor wajib diisi.',
            'title.required' => 'Judul laporan aktivitas wajib diisi.',
            'description.required' => 'Deskripsi aktivitas dan progress wajib diisi.',
            'description.min' => 'Deskripsi kegiatan minimal 10 karakter.',
            'photo.image' => 'File dokumentasi harus berupa gambar (JPG, PNG, atau WEBP).',
            'photo.max' => 'Ukuran foto dokumentasi maksimal 5 MB.',
        ]);

        // 4. Upload Foto jika ada
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

        // 5. Simpan Laporan
        $report = DailyProgressReport::create([
            'daily_report_link_id' => $link->id,
            'group_id' => $link->group_id,
            'user_id' => auth()->id(), // null jika publik tanpa login
            'reporter_name' => $validated['reporter_name'],
            'report_date' => $todayDate,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'photo_url' => $photoUrl,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'submitted',
        ]);

        return redirect()->route('public.report.show', $link->token)
            ->with('success', 'Laporan progress harian berhasil dikirim dan tersimpan di sistem. Terima kasih!');
    }
}
