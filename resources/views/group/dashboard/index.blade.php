@extends('group.layouts.app')

@section('title', 'Beranda Kelompok — RUMA')

@section('content')
<div class="space-y-5">

    <!-- Greeting & Group Profile Card -->
    <div class="bg-gradient-to-br from-[#FDFBF7] to-[#F8F2E4] rounded-2xl p-4 border border-[#B88E3E]/20 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E6920]">RUMA • AKUN KELOMPOK</span>
                <h2 class="text-lg font-black text-slate-900 mt-0.5">{{ $group->name ?? 'KWT Sumber Rejeki' }}</h2>
                <p class="text-xs font-semibold text-slate-600 flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Admin: {{ $user->name }}</span>
                    <span class="text-slate-400">•</span>
                    <span>{{ $group->regency ?? 'Tasikmalaya' }}</span>
                </p>
            </div>
            @if($group && $group->logo_url)
            <img src="{{ $group->logo_url }}" alt="Logo" class="w-12 h-12 rounded-xl object-contain bg-white p-1 border border-amber-200/50 shadow-xs">
            @endif
        </div>
    </div>

    <!-- Laporan Progress Harian Shortcut -->
    <a href="{{ route('group.progress.index') }}" class="bg-gradient-to-r from-[#FDFBF7] to-[#F8F2E4] rounded-2xl p-3.5 border border-[#B88E3E]/30 shadow-xs flex items-center justify-between hover:border-[#B88E3E] transition-all">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#B88E3E] text-white flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <span class="text-xs font-black text-slate-900 block">Laporan Progress Harian</span>
                <span class="text-[10px] text-slate-500 font-medium">Kirim laporan atau bagikan tautan cepat tanpa login &rarr;</span>
            </div>
        </div>
        <svg class="w-4 h-4 text-[#B88E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
    </a>

    <!-- 1. APA YANG HARUS SAYA KERJAKAN? -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>Tugas Berikutnya</span>
            </h3>
            <a href="{{ route('group.marketing.index') }}" class="text-[11px] font-bold text-[#B88E3E] hover:underline flex items-center gap-1">
                <span>Semua Tugas</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($activeTask)
        <div class="bg-white rounded-2xl border-2 border-[#B88E3E]/40 p-4 shadow-sm space-y-3 relative overflow-hidden">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-50 text-[#8E6920] border border-amber-200">
                        {{ $activeTask->priority === 'tinggi' ? 'Prioritas Tinggi' : 'Tugas Fasilitator' }}
                    </span>
                    <h4 class="text-sm font-extrabold text-slate-900 mt-1.5">{{ $activeTask->title }}</h4>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $activeTask->description }}</p>
                </div>
                @if($activeTask->reference_image_url)
                <img src="{{ $activeTask->reference_image_url }}" alt="Panduan" class="w-16 h-16 rounded-xl object-cover shrink-0 border border-slate-200">
                @endif
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                <span class="text-slate-400 font-medium">Batas: <b class="text-slate-700">{{ $activeTask->deadline->format('d M, H:i') }}</b></span>
                
                @if($mySubmission)
                    @if($mySubmission->status === 'disetujui')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                        ✓ Disetujui
                    </span>
                    @elseif($mySubmission->status === 'perlu_revisi')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800">
                        Perlu Revisi
                    </span>
                    @else
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-sky-100 text-sky-800">
                        Menunggu Review
                    </span>
                    @endif
                @else
                <a href="{{ route('group.marketing.index') }}" class="px-4 py-2 rounded-xl bg-[#B88E3E] text-white font-extrabold text-xs shadow-xs hover:bg-[#9E752D] transition-colors">
                    Kerjakan Sekarang
                </a>
                @endif
            </div>
        </div>
        @else
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-center text-xs text-slate-500">
            Tidak ada tugas mendesak saat ini. Kelompok Anda telah mengerjakan semua arahan fasilitator.
        </div>
        @endif
    </div>

    <!-- 2. BAGAIMANA PERKEMBANGAN KELOMPOK SAYA? (SVG Icons) -->
    <div class="space-y-2">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Perkembangan Kelompok</h3>
        
        <div class="grid grid-cols-2 gap-2.5">
            <!-- Komoditas -->
            <a href="{{ route('group.commodities.index') }}" class="bg-white rounded-2xl p-3.5 border border-slate-200/90 shadow-xs hover:border-[#B88E3E]/50 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400">Komoditas</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-2xl font-black text-slate-900">{{ $commoditiesCount }}</span>
                    <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ $commoditiesList }}</p>
                </div>
            </a>

            <!-- Produk -->
            <a href="{{ route('group.products.index') }}" class="bg-white rounded-2xl p-3.5 border border-slate-200/90 shadow-xs hover:border-[#B88E3E]/50 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400">Produk</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-2xl font-black text-slate-900">{{ $productsCount }}</span>
                    <p class="text-[10px] text-slate-500 truncate mt-0.5">Produk terdaftar & QC</p>
                </div>
            </a>

            <!-- Keuangan Saldo -->
            <a href="{{ route('group.finance.index') }}" class="bg-white rounded-2xl p-3.5 border border-slate-200/90 shadow-xs hover:border-[#B88E3E]/50 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400">Kas Bersih</span>
                    <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-lg font-black text-slate-900">Rp {{ number_format($totalBalance, 0, ',', '.') }}</span>
                    <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">+{{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
            </a>

            <!-- Tugas Selesai -->
            <a href="{{ route('group.marketing.index') }}" class="bg-white rounded-2xl p-3.5 border border-slate-200/90 shadow-xs hover:border-[#B88E3E]/50 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400">Tugas Selesai</span>
                    <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-2xl font-black text-slate-900">{{ $completedTasks }} <span class="text-xs text-slate-400 font-normal">/ {{ $totalTasks }}</span></span>
                    <p class="text-[10px] text-slate-500 mt-0.5">Tugas terlaksana</p>
                </div>
            </a>
        </div>
    </div>

    <!-- 3. APA YANG PERLU DIPERBAIKI? (Wadah Terpisah) -->
    @if(count($revisionItems) > 0)
    <div class="space-y-2">
        <h3 class="text-xs font-black uppercase tracking-wider text-rose-700 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
            <span>Perlu Diperbaiki (Feedback Fasilitator)</span>
        </h3>

        @foreach($revisionItems as $item)
        <a href="{{ $item['link'] }}" class="block bg-rose-50/60 border border-rose-200 rounded-2xl p-3.5 space-y-1.5 hover:bg-rose-50 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-rose-200/70 text-rose-800">
                    {{ $item['type'] }}
                </span>
                <span class="text-xs font-bold text-rose-700">&rarr;</span>
            </div>
            <h4 class="text-xs font-extrabold text-slate-900">{{ $item['title'] }}</h4>
            <p class="text-xs text-rose-900 leading-snug font-medium">{{ $item['note'] }}</p>
        </a>
        @endforeach
    </div>
    @endif

    <!-- Jadwal Terdekat -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Jadwal Pertemuan Terdekat</h3>
            <a href="{{ route('group.learning.index', ['tab' => 'ruangan']) }}" class="text-[11px] font-bold text-[#B88E3E] hover:underline flex items-center gap-1">
                <span>Semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="space-y-2">
            @forelse($upcomingSchedules as $sched)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-3 flex items-center gap-3 shadow-xs">
                <div class="w-12 h-12 rounded-xl bg-[#F8F2E4] border border-[#B88E3E]/20 flex flex-col items-center justify-center text-center shrink-0">
                    <span class="text-[9px] font-extrabold uppercase text-[#8E6920]">{{ $sched->badge_month ?? $sched->event_date->format('M') }}</span>
                    <span class="text-sm font-black text-slate-900 leading-none">{{ $sched->badge_day ?? $sched->event_date->format('d') }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h5 class="text-xs font-extrabold text-slate-900 truncate">{{ $sched->title }}</h5>
                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $sched->time_info }} • {{ $sched->location_or_link ?? 'Online Zoom' }}</p>
                </div>
            </div>
            @empty
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-xs text-slate-400">
                Belum ada jadwal kelas atau mentoring baru.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Navigation Grid (Using Clean Vector SVGs) -->
    <div class="pt-2">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-2">Pintasan Fitur</h3>
        <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <a href="{{ route('group.learning.index') }}" class="p-3 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-[#B88E3E]/50 flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#B88E3E] flex items-center justify-center mb-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                </div>
                <span class="font-extrabold text-slate-800 text-[11px]">Belajar</span>
            </a>
            <a href="{{ route('group.land.index') }}" class="p-3 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-[#B88E3E]/50 flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-[#1E4D2B] flex items-center justify-center mb-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                </div>
                <span class="font-extrabold text-slate-800 text-[11px]">Lahan</span>
            </a>
            <a href="{{ route('group.finance.index') }}" class="p-3 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-[#B88E3E]/50 flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center mb-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="font-extrabold text-slate-800 text-[11px]">Keuangan</span>
            </a>
            <a href="{{ route('group.products.index') }}" class="p-3 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-[#B88E3E]/50 flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#8E6920] flex items-center justify-center mb-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <span class="font-extrabold text-slate-800 text-[11px]">Produk QC</span>
            </a>
            <a href="{{ route('group.marketing.index') }}" class="p-3 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-[#B88E3E]/50 flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center mb-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                </div>
                <span class="font-extrabold text-slate-800 text-[11px]">Marketing</span>
            </a>
            <a href="{{ route('group.account.index') }}" class="p-3 rounded-2xl bg-white border border-slate-200 shadow-xs hover:border-[#B88E3E]/50 flex flex-col items-center justify-center">
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center mb-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="font-extrabold text-slate-800 text-[11px]">Kelompok</span>
            </a>
        </div>
    </div>

</div>
@endsection
