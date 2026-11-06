@extends('facilitator.layouts.app')

@section('title', 'Detail Kelompok ' . $group->name . ' — Fasilitator SiYota')

@section('content')
<div class="space-y-6">

    <!-- Back Button & Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('facilitator.dashboard') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#1E4D2B]">MONITORING KELOMPOK</span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs font-semibold text-slate-500">{{ $group->regency }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $group->name }}</h1>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="ruma-card rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-bold uppercase">Saldo Kas</div>
            <div class="text-xl font-black text-slate-900 mt-1">Rp{{ number_format($group->total_balance, 0, ',', '.') }}</div>
        </div>
        <div class="ruma-card rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-bold uppercase">Lahan Terdata</div>
            <div class="text-xl font-black text-slate-900 mt-1">{{ $group->lands->count() }} Titik</div>
        </div>
        <div class="ruma-card rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-bold uppercase">Komoditas</div>
            <div class="text-xl font-black text-slate-900 mt-1">{{ $group->commodities->count() }} Jenis</div>
        </div>
        <div class="ruma-card rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-bold uppercase">Produk Olahan</div>
            <div class="text-xl font-black text-slate-900 mt-1">{{ $group->products->count() }} Produk</div>
        </div>
    </div>

    <!-- Detail Lahan & Komoditas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Lahan -->
        <div class="ruma-card rounded-3xl p-5 space-y-3">
            <h3 class="font-extrabold text-slate-900 text-sm">Sebaran Titik Lahan</h3>
            <div class="space-y-2.5">
                @forelse($group->lands as $l)
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">{{ $l->name }}</div>
                            <div class="text-slate-500">{{ $l->commodities }} • {{ $l->land_status }}</div>
                        </div>
                        <span class="font-bold text-slate-700">{{ number_format($l->area_m2) }} m²</span>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 py-3">Belum ada lahan terdata.</div>
                @endforelse
            </div>
        </div>

        <!-- Pengurus & Anggota -->
        <div class="ruma-card rounded-3xl p-5 space-y-3">
            <h3 class="font-extrabold text-slate-900 text-sm">Pengelola Kelompok</h3>
            <div class="space-y-2.5">
                @forelse($group->members as $m)
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">{{ $m->name }}</div>
                            <div class="text-slate-500">{{ $m->position_title }} • {{ $m->email }}</div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white border border-slate-200">
                            {{ $m->phone ?? '-' }}
                        </span>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 py-3">Belum ada anggota terdaftar.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Riwayat Mentoring Kelompok -->
    <div class="ruma-card rounded-3xl p-6 space-y-4">
        <h3 class="font-extrabold text-slate-900 text-base">Riwayat Mentoring & Kunjungan Lapangan</h3>
        <div class="space-y-3">
            @forelse($group->mentoringNotes as $mn)
                <div class="p-4 rounded-2xl bg-[#FDFBF7] border border-[#E6CE93]/50 text-xs sm:text-sm space-y-2">
                    <div class="flex items-center justify-between">
                        <strong class="text-slate-900">{{ $mn->topic }}</strong>
                        <span class="text-slate-500 font-semibold">{{ $mn->date->format('d F Y') }}</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">{{ $mn->observations }}</p>
                    @if($mn->action_items)
                        <div class="text-amber-900 font-medium bg-white p-2.5 rounded-xl border border-amber-200">
                            <strong>Rekomendasi:</strong> {{ $mn->action_items }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-xs text-slate-400 py-4 text-center">Belum ada riwayat pendampingan.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection

