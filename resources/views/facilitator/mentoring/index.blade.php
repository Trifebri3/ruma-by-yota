@extends('facilitator.layouts.app')

@section('title', 'Log Mentoring Lapangan — Fasilitator SiYota')

@section('content')
<div class="space-y-6 pb-16">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#1E4D2B]">Pendampingan Komunitas</span>
            <h1 class="text-2xl font-black text-slate-900">Catatan Mentoring & Kunjungan Lapangan</h1>
        </div>
        <a href="{{ route('facilitator.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Kembali</a>
    </div>

    <div class="space-y-4">
        @forelse($notes as $note)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold uppercase text-[#B88E3E]">{{ $note->group->name ?? 'Kelompok' }}</span>
                    <h3 class="text-base font-extrabold text-slate-900">{{ $note->topic }}</h3>
                </div>
                <span class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::parse($note->date)->format('d F Y') }}</span>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">{{ $note->observations }}</p>

            @if($note->action_items)
            <div class="p-3 bg-emerald-50/70 border border-emerald-200/50 rounded-xl text-xs text-emerald-900">
                <b class="text-emerald-800">Tindak Lanjut yang Disepakati:</b> {{ $note->action_items }}
            </div>
            @endif
        </div>
        @empty
        <div class="p-8 text-center bg-slate-50 rounded-2xl text-xs text-slate-500">
            Belum ada catatan mentoring tersimpan.
        </div>
        @endforelse
    </div>
</div>
@endsection

