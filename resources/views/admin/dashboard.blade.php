@extends('admin.layouts.app')

@section('title', 'Super Admin Dashboard — RUMA SiYota')

@section('content')
<div class="space-y-8 pb-16">

    <!-- Admin Welcome Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#B88E3E] text-white">
                SUPER ADMIN PLATFORM
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">RUMA SiYota Command Center</h1>
            <p class="text-slate-300 text-xs sm:text-sm">Pusat kendali kelompok binaan, program pendampingan, kurasi QC, dan gerbang notifikasi.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.approvals.index') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs transition-colors shadow-xs">
                Antrean Verifikasi ({{ $pendingGroups }})
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs border border-slate-700 transition-colors">
                Pengaturan Notifikasi
            </a>
        </div>
    </div>

    <!-- Metric Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">Total Kelompok</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $totalGroups }}</span>
            <span class="text-[10px] text-emerald-600 font-semibold">{{ $activeGroups }} Aktif Binaan</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">Menunggu Verifikasi</span>
            <span class="text-xl font-black text-amber-600 mt-1 block">{{ $pendingGroups }}</span>
            <span class="text-[10px] text-amber-700 font-semibold">Pendaftaran baru</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">Fasilitator Lapangan</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $facilitatorsCount }}</span>
            <span class="text-[10px] text-slate-500 font-semibold">Tim pendamping</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">Titik Lahan</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $totalLands }}</span>
            <span class="text-[10px] text-[#1E4D2B] font-semibold">Polygon terdata</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">Katalog Produk</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $totalProducts }}</span>
            <span class="text-[10px] text-slate-500 font-semibold">Produk hasil usaha</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">Tugas Menunggu</span>
            <span class="text-xl font-black text-purple-600 mt-1 block">{{ $pendingReviews }}</span>
            <span class="text-[10px] text-purple-700 font-semibold">Review kurasi</span>
        </div>
    </div>

    <!-- Admin Quick Navigation Bar -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-bold hide-scrollbar">
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white whitespace-nowrap">
            Dashboard Utama
        </a>
        <a href="{{ route('admin.groups.index') }}" class="px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 whitespace-nowrap">
            Kelompok Tani
        </a>
        <a href="{{ route('admin.approvals.index') }}" class="px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 whitespace-nowrap">
            Antrean Verifikasi (Approval)
        </a>
        <a href="{{ route('admin.programs.index') }}" class="px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 whitespace-nowrap">
            Program Pendampingan
        </a>
        <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 whitespace-nowrap">
            Notifikasi & Gateway
        </a>
    </div>

    <!-- Main Content: Pending Approvals & Notification Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left: Kelompok Menunggu Persetujuan -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-extrabold text-slate-900">Pendaftaran Kelompok Baru (Perlu Verifikasi)</h2>
                <a href="{{ route('admin.approvals.index') }}" class="text-xs font-bold text-[#B88E3E] hover:underline">Semua &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentPending as $p)
                <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200/80 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-sm">{{ $p->name }}</h4>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-200 text-amber-900">Menunggu</span>
                    </div>
                    <p class="text-slate-600">{{ $p->regency }} • Kontak: {{ $p->contact_phone }} ({{ $p->leader_name }})</p>
                    <p class="text-slate-700">Komoditas: <b>{{ $p->commodity_summary ?? '-' }}</b></p>
                    
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <form action="{{ route('admin.approvals.verify', $p->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="active">
                            <input type="hidden" name="verification_notes" value="Disetujui oleh Super Admin.">
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#1E4D2B] hover:bg-[#143D24] text-white font-bold text-xs">
                                ✓ Setujui & Aktifkan
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl">
                    Tidak ada pendaftaran kelompok yang tertunda saat ini.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Recent Notification Logs (WhatsApp & Email) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-extrabold text-slate-900">Log Aktivitas Notifikasi Sistem</h2>
                <a href="{{ route('admin.notifications.index') }}" class="text-xs font-bold text-[#B88E3E] hover:underline">Kelola &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentNotifications as $notif)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5">
                            @if($notif->channel === 'whatsapp')
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span>WhatsApp</span>
                            @else
                                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>Email</span>
                            @endif
                            <span class="text-slate-400">• {{ $notif->event_type }}</span>
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                            {{ $notif->status }}
                        </span>
                    </div>
                    <p class="text-slate-600 line-clamp-1 italic">"{{ $notif->message_preview }}"</p>
                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1">
                        <span>Penerima: {{ $notif->recipient }} ({{ $notif->group->name ?? 'User' }})</span>
                        <span>{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl">
                    Belum ada riwayat pengiriman pesan notifikasi.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

