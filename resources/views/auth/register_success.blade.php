@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil Dikirim — RUMA SiYota')

@section('content')
<div class="max-w-xl mx-auto py-10 space-y-6 text-center">

    <div class="w-20 h-20 rounded-3xl bg-emerald-50 text-[#1E4D2B] border border-emerald-200 flex items-center justify-center mx-auto text-3xl shadow-xs">
        ✓
    </div>

    <div class="space-y-2">
        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-100 text-amber-900 border border-amber-300">
            Status: Menunggu Verifikasi
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Pendaftaran Berhasil Dikirim!
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto leading-relaxed">
            Terima kasih telah mendaftarkan <strong>{{ $group->name ?? 'Kelompok Anda' }}</strong> ke dalam ekosistem pendampingan digital RUMA SiYota.
        </p>
    </div>

    <div class="ruma-card rounded-3xl p-6 text-left space-y-3">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Rincian Pengajuan:</h3>
        <div class="space-y-2 text-xs text-slate-700">
            <div class="flex justify-between py-1 border-b border-slate-100">
                <span class="text-slate-500">Nama Kelompok:</span>
                <strong class="text-slate-900">{{ $group->name ?? '-' }}</strong>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-100">
                <span class="text-slate-500">Wilayah:</span>
                <strong class="text-slate-900">{{ $group->district ?? '' }}, {{ $group->regency ?? '' }}</strong>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-100">
                <span class="text-slate-500">Ketua / Kontak:</span>
                <strong class="text-slate-900">{{ $group->leader_name ?? '-' }} ({{ $group->contact_phone ?? '-' }})</strong>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-slate-500">Komoditas Utama:</span>
                <strong class="text-[#B88E3E]">{{ $group->commodity_summary ?? '-' }}</strong>
            </div>
        </div>
    </div>

    <!-- Petunjuk Selanjutnya -->
    <div class="p-4 rounded-2xl bg-[#FDFBF7] border border-[#E6CE93]/60 text-xs text-slate-600 text-left space-y-1.5">
        <div class="font-bold text-slate-800">Langkah Berikutnya:</div>
        <ol class="list-decimal pl-4 space-y-1 leading-relaxed">
            <li>Admin SiYota akan memeriksa berkas dan menghubungi nomor WhatsApp Anda.</li>
            <li>Setelah verifikasi disetujui, akun ketua kelompok akan diaktifkan secara otomatis.</li>
            <li>Kelompok dapat langsung mengakses dashboard RUMA untuk mengundang anggota, mencatat kas, dan mengikuti kelas pendampingan.</li>
        </ol>
    </div>

    <div class="pt-2 flex items-center justify-center gap-3">
        <a href="{{ route('switch.persona', 'admin_siyota') }}" class="px-5 py-2.5 rounded-2xl bg-[#1E4D2B] text-white text-xs font-bold shadow-xs hover:bg-[#15381F] transition-all">
            Lihat Antrean di Admin SiYota →
        </a>
        <a href="{{ route('ruma.dashboard') }}" class="px-5 py-2.5 rounded-2xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 transition-all">
            Kembali ke Beranda
        </a>
    </div>

</div>
@endsection

