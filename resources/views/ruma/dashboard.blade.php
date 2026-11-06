@extends('group.layouts.app')

@section('title', 'Dashboard Kelompok — ' . ($group->name ?? 'KWT Sumber Rejeki'))

@section('content')
<div class="space-y-6">

    <!-- Header Salam Kelompok -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold tracking-wider uppercase text-[#B88E3E]">RUMA • KELOMPOK</span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ ucfirst($group->status ?? 'Aktif') }}
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Selamat datang, {{ $user->name ? explode(' ', $user->name)[0] : 'Bu' }} {{ count(explode(' ', $user->name)) > 1 ? explode(' ', $user->name)[1] : '' }}!
            </h1>
            <p class="text-sm font-medium text-slate-500 flex items-center gap-1.5">
                <span class="font-bold text-slate-700">{{ $group->name ?? 'KWT Sumber Rejeki' }}</span>
                <span>•</span>
                <span>{{ $group->regency ?? 'Kabupaten Tasikmalaya' }}</span>
            </p>
        </div>

        <!-- Quick Action / Status Chip -->
        <div class="flex items-center gap-2">
            <a href="{{ route('ruma.belajar', ['tab' => 'jadwal']) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 shadow-xs hover:border-[#B88E3E] hover:text-[#B88E3E] transition-all">
                <svg class="w-4 h-4 text-[#B88E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Jadwal Agenda</span>
            </a>
            <a href="{{ route('ruma.usaha', ['tab' => 'keuangan']) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white text-xs font-bold shadow-xs transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Catat Kas</span>
            </a>
        </div>
    </div>

    <!-- 1. APA YANG HARUS SAYA KERJAKAN? (Highlight Tugas Berikutnya) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-50/70 via-white to-amber-50/40 border border-[#E6CE93]/60 p-5 sm:p-6 shadow-sm">
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 rounded-full bg-[#B88E3E]/10 blur-2xl pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide bg-[#B88E3E] text-white shadow-xs">
                        TUGAS BERIKUTNYA
                    </span>
                    <span class="text-xs text-slate-500 font-medium">
                        Batas: {{ $activeTask ? $activeTask->deadline->format('d F Y') : '12 November 2026' }}
                    </span>
                </div>
                
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-snug">
                    {{ $activeTask->title ?? 'Foto produk cabai & sambal' }}
                </h3>
                
                <p class="text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
                    {{ $activeTask->description ?? 'Ambil 3 foto produk dengan pencahayaan yang baik sesuai panduan dari fasilitator SiYota.' }}
                </p>

                <!-- Status Tugas Badge -->
                <div class="pt-1 flex items-center gap-2">
                    @php
                        $subStatus = $mySubmission?->status ?? 'belum_dikerjakan';
                    @endphp
                    @if($subStatus === 'belum_dikerjakan')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100/90 text-amber-900 border border-amber-300">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Belum dikerjakan
                        </span>
                    @elseif($subStatus === 'perlu_revisi')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                            Perlu Revisi Fasilitator
                        </span>
                    @elseif($subStatus === 'dikumpulkan')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Menunggu Review Fasilitator
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Disetujui
                        </span>
                    @endif
                    <span class="text-xs text-slate-500">Fasilitator: <strong class="text-slate-700">Kang Rian (SiYota)</strong></span>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="flex-shrink-0">
                <a href="{{ route('ruma.marketing') }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-sm shadow-md shadow-[#B88E3E]/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <span>Kerjakan Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. BAGAIMANA PERKEMBANGAN KELOMPOK SAYA? (4 Kartu Ringkas Sesuai Brief) -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Ringkasan Perkembangan Kelompok
            </h2>
            <span class="text-[11px] text-slate-400">Pembaruan otomatis</span>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            
            <!-- Card 1: Komoditas -->
            <a href="{{ route('ruma.usaha', ['tab' => 'komoditas']) }}" class="ruma-card ruma-card-hover rounded-3xl p-4 sm:p-5 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 group-hover:text-[#B88E3E] transition-colors">Komoditas</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-[#1E4D2B] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $commoditiesCount }}
                    </div>
                    <div class="text-xs text-slate-500 truncate mt-0.5">
                        {{ $commoditiesList }}
                    </div>
                </div>
            </a>

            <!-- Card 2: Produk -->
            <a href="{{ route('ruma.usaha', ['tab' => 'produk']) }}" class="ruma-card ruma-card-hover rounded-3xl p-4 sm:p-5 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 group-hover:text-[#B88E3E] transition-colors">Produk</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#B88E3E] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $productsCount }}
                    </div>
                    <div class="text-xs text-slate-500 truncate mt-0.5">
                        Produk terdaftar
                    </div>
                </div>
            </a>

            <!-- Card 3: Keuangan -->
            <a href="{{ route('ruma.usaha', ['tab' => 'keuangan']) }}" class="ruma-card ruma-card-hover rounded-3xl p-4 sm:p-5 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 group-hover:text-[#B88E3E] transition-colors">Keuangan</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                        Rp{{ number_format($totalBalance, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-emerald-600 font-medium truncate mt-0.5">
                        Saldo kas tercatat
                    </div>
                </div>
            </a>

            <!-- Card 4: Tugas -->
            <a href="{{ route('ruma.marketing') }}" class="ruma-card ruma-card-hover rounded-3xl p-4 sm:p-5 flex flex-col justify-between group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 group-hover:text-[#B88E3E] transition-colors">Tugas</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $completedTasks }} / {{ $totalTasks }}
                    </div>
                    <div class="text-xs text-slate-500 truncate mt-0.5">
                        Tugas selesai
                    </div>
                </div>
            </a>

        </div>
    </div>

    <!-- 3. APA YANG PERLU DIPERBAIKI? (Catatan Evaluasi / Feedback Fasilitator) -->
    @if(count($revisionItems) > 0)
    <div class="rounded-3xl bg-rose-50/60 border border-rose-200/80 p-4 sm:p-5 space-y-3">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
            <span class="text-xs font-bold uppercase tracking-wide text-rose-800">
                APA YANG PERLU DIPERBAIKI DARI FASILITATOR?
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($revisionItems as $item)
                <div class="p-3.5 bg-white rounded-2xl border border-rose-100 shadow-xs flex flex-col justify-between space-y-2">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-rose-100 text-rose-700">
                                {{ $item['type'] }}
                            </span>
                            <span class="text-xs font-bold text-slate-800 truncate">{{ $item['title'] }}</span>
                        </div>
                        <p class="text-xs text-slate-600 mt-2 italic leading-relaxed">
                            "{{ $item['note'] }}"
                        </p>
                    </div>
                    <div class="pt-1 flex justify-end">
                        <a href="{{ $item['link'] }}" class="text-xs font-bold text-[#B88E3E] hover:underline inline-flex items-center gap-1">
                            <span>Perbaiki Sekarang</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- GRID DUA KOLOM: JADWAL TERDEKAT & TRANSAKSI TERAKHIR -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Jadwal Terdekat Sesuai Brief (NOV 08 Pelatihan Branding Produk 09.00 WIB • Online Zoom) -->
        <div class="ruma-card rounded-3xl p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-100 text-[#B88E3E] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm tracking-tight">Jadwal Terdekat</h3>
                </div>
                <a href="{{ route('ruma.belajar', ['tab' => 'jadwal']) }}" class="text-xs font-semibold text-[#B88E3E] hover:underline">
                    Lihat Semua
                </a>
            </div>

            <div class="space-y-3">
                @forelse($upcomingSchedules as $sch)
                    <div class="p-3.5 rounded-2xl border border-slate-100 hover:border-amber-200 bg-[#FDFBF7]/50 flex items-center gap-3.5 transition-all">
                        <!-- Date Badge (e.g. NOV 08) -->
                        <div class="w-12 h-12 rounded-xl bg-white border border-[#E6CE93]/60 shadow-2xs flex flex-col items-center justify-center flex-shrink-0 text-center">
                            <span class="text-[9px] font-bold text-[#B88E3E] uppercase tracking-wider leading-none">
                                {{ $sch->badge_month ?? $sch->event_date->format('M') }}
                            </span>
                            <span class="text-base font-extrabold text-slate-900 leading-tight">
                                {{ $sch->badge_day ?? $sch->event_date->format('d') }}
                            </span>
                        </div>
                        
                        <!-- Schedule Info -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                {{ $sch->title }}
                            </h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 truncate">
                                {{ $sch->time_info }}
                            </p>
                        </div>

                        <!-- Action -->
                        @if($sch->location_or_link && str_contains($sch->location_or_link, 'zoom'))
                            <a href="{{ $sch->location_or_link }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-2xs flex-shrink-0">
                                Buka Zoom
                            </a>
                        @else
                            <a href="{{ route('ruma.belajar', ['tab' => 'jadwal']) }}" class="text-slate-400 hover:text-slate-600 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-slate-400">
                        Belum ada jadwal terdekat.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Transaksi Terakhir (Sesuai Brief: Penjualan cabai +Rp500.000, Pembelian kemasan −Rp150.000) -->
        <div class="ruma-card rounded-3xl p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-[#1E4D2B] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm tracking-tight">Transaksi Terakhir</h3>
                </div>
                <a href="{{ route('ruma.usaha', ['tab' => 'keuangan']) }}" class="text-xs font-semibold text-[#B88E3E] hover:underline">
                    Buka Catatan Kas
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentTransactions as $tx)
                    <div class="p-3.5 rounded-2xl border border-slate-100 bg-white flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl {{ $tx->type === 'masuk' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ $tx->type === 'masuk' ? '+' : '−' }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                    {{ $tx->category }}
                                </h4>
                                <p class="text-[11px] text-slate-500 truncate">
                                    {{ $tx->transaction_date->format('d F Y') }} • {{ $tx->description }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs sm:text-sm font-extrabold {{ $tx->type === 'masuk' ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $tx->type === 'masuk' ? '+' : '−' }}Rp{{ number_format($tx->amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-slate-400">
                        Belum ada transaksi dicatat.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- MENU UTAMA AKSES CEPAT (6 Menu: Belajar, Lahan, Keuangan, Produk, Marketing, Kelompok) -->
    <div class="pt-2">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
            Menu Akses Cepat Kelompok
        </h3>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            
            <a href="{{ route('ruma.belajar') }}" class="ruma-card ruma-card-hover rounded-2xl p-4 text-center group flex flex-col items-center">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-[#B88E3E] group-hover:scale-110 flex items-center justify-center transition-transform mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800">Belajar</span>
                <span class="text-[10px] text-slate-400">Kelas & Zoom</span>
            </a>

            <a href="{{ route('ruma.usaha', ['tab' => 'lahan']) }}" class="ruma-card ruma-card-hover rounded-2xl p-4 text-center group flex flex-col items-center">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#1E4D2B] group-hover:scale-110 flex items-center justify-center transition-transform mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800">Lahan & Peta</span>
                <span class="text-[10px] text-slate-400">Sebaran Wilayah</span>
            </a>

            <a href="{{ route('ruma.usaha', ['tab' => 'keuangan']) }}" class="ruma-card ruma-card-hover rounded-2xl p-4 text-center group flex flex-col items-center">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 group-hover:scale-110 flex items-center justify-center transition-transform mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800">Keuangan</span>
                <span class="text-[10px] text-slate-400">Uang Masuk/Keluar</span>
            </a>

            <a href="{{ route('ruma.usaha', ['tab' => 'produk']) }}" class="ruma-card ruma-card-hover rounded-2xl p-4 text-center group flex flex-col items-center">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 group-hover:scale-110 flex items-center justify-center transition-transform mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800">Produk & QC</span>
                <span class="text-[10px] text-slate-400">Kemasan & Mutu</span>
            </a>

            <a href="{{ route('ruma.marketing') }}" class="ruma-card ruma-card-hover rounded-2xl p-4 text-center group flex flex-col items-center">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 group-hover:scale-110 flex items-center justify-center transition-transform mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800">Marketing Hub</span>
                <span class="text-[10px] text-slate-400">Tugas & Konten</span>
            </a>

            <a href="{{ route('ruma.kelompok') }}" class="ruma-card ruma-card-hover rounded-2xl p-4 text-center group flex flex-col items-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 group-hover:scale-110 flex items-center justify-center transition-transform mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800">Kelompok</span>
                <span class="text-[10px] text-slate-400">Anggota & Profil</span>
            </a>

        </div>
    </div>

</div>
@endsection

