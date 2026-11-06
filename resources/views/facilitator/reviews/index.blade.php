@extends('facilitator.layouts.app')

@section('title', 'Review Kiriman Tugas Kelompok — Fasilitator SiYota')

@section('content')
<div class="space-y-6 pb-16">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#1E4D2B]">Evaluasi & Kurasi</span>
            <h1 class="text-2xl font-black text-slate-900">Review Kiriman Tugas Marketing</h1>
        </div>
        <a href="{{ route('facilitator.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($submissions as $sub)
        <div class="bg-white rounded-2xl border-2 {{ $sub->status === 'dikumpulkan' ? 'border-amber-300' : ($sub->status === 'perlu_revisi' ? 'border-rose-300' : 'border-slate-200/90') }} p-5 shadow-xs flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-[#1E4D2B]">{{ $sub->group->name ?? 'Kelompok' }}</span>
                        <h3 class="text-sm font-extrabold text-slate-900 mt-0.5">{{ $sub->task->title ?? 'Tugas Konten' }}</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $sub->status === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : ($sub->status === 'perlu_revisi' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $sub->status)) }}
                    </span>
                </div>

                @if($sub->photo_url)
                <div class="rounded-xl overflow-hidden border border-slate-200">
                    <img src="{{ $sub->photo_url }}" alt="Foto Tugas" class="w-full h-44 object-cover">
                </div>
                @endif

                <div class="bg-slate-50 p-3 rounded-xl space-y-1 text-xs">
                    <span class="font-bold text-slate-700 block">Caption yang Diajukan Kelompok:</span>
                    <p class="text-slate-800 italic leading-relaxed">"{{ $sub->caption }}"</p>
                    @if($sub->notes)
                    <p class="text-[11px] text-slate-500 mt-1">Catatan: {{ $sub->notes }}</p>
                    @endif
                </div>

                @if($sub->facilitator_feedback)
                <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-200 text-xs text-amber-900 space-y-0.5">
                    <span class="font-extrabold block">Feedback Terakhir Anda:</span>
                    <p>{{ $sub->facilitator_feedback }}</p>
                </div>
                @endif
            </div>

            <!-- Form Penilaian Fasilitator -->
            <form action="{{ route('facilitator.reviews.submission', $sub->id) }}" method="POST" class="pt-3 border-t border-slate-100 space-y-2.5 text-xs">
                @csrf
                <div class="flex items-center gap-2">
                    <label class="font-bold text-slate-700 shrink-0">Keputusan:</label>
                    <select name="status" required class="flex-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white font-bold">
                        <option value="disetujui" {{ $sub->status === 'disetujui' ? 'selected' : '' }}>✓ Setujui (Bagus)</option>
                        <option value="perlu_revisi" {{ $sub->status === 'perlu_revisi' ? 'selected' : '' }}>✕ Perlu Revisi</option>
                        <option value="dipublikasikan" {{ $sub->status === 'dipublikasikan' ? 'selected' : '' }}>Siap Publikasikan ke Web</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan & Masukan Perbaikan (Otomatis Dikirim via WhatsApp):</label>
                    <textarea name="facilitator_feedback" rows="2" required placeholder="Tuliskan arahan perbaikan sudut pengambilan foto, pencahayaan, atau saran caption..." class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ $sub->facilitator_feedback }}</textarea>
                </div>

                <div class="text-right">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1E4D2B] hover:bg-[#143D24] text-white font-bold text-xs shadow-xs transition-colors">
                        Simpan & Kirim Notifikasi
                    </button>
                </div>
            </form>
        </div>
        @empty
        <div class="col-span-full p-8 text-center bg-slate-50 rounded-2xl text-xs text-slate-500">
            Belum ada kiriman tugas dari kelompok binaan.
        </div>
        @endforelse
    </div>
</div>
@endsection

