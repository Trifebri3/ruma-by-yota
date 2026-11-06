@extends('facilitator.layouts.app')

@section('title', 'Dashboard Fasilitator SiYota — RUMA')

@section('content')
<div class="space-y-8 pb-16">

    <!-- Top Facilitator Welcome Header -->
    <div class="bg-gradient-to-r from-[#1E4D2B] to-[#143D24] text-white rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#B88E3E] text-white">
                FASILITATOR SIYOTA
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Halo, {{ $user->name }}!</h1>
            <p class="text-emerald-100 text-xs sm:text-sm">Mendampingi kemandirian kelompok usaha berbasis hasil bumi lokal.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('facilitator.tasks.index') }}" class="px-4 py-2.5 rounded-xl bg-white text-[#1E4D2B] font-bold text-xs hover:bg-emerald-50 transition-colors shadow-xs">
                + Buat Tugas Baru
            </a>
            <a href="{{ route('facilitator.reviews.index') }}" class="px-4 py-2.5 rounded-xl bg-[#B88E3E] text-white font-bold text-xs hover:bg-[#9E752D] transition-colors shadow-xs">
                Review Kiriman ({{ $pendingReviewsCount }})
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs">
            <span class="text-xs font-bold text-slate-400 block uppercase">Kelompok Binaan</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $groups->count() }}</span>
            <span class="text-[11px] text-[#1E4D2B] font-semibold">Aktif didampingi</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs">
            <span class="text-xs font-bold text-slate-400 block uppercase">Menunggu Review</span>
            <span class="text-2xl font-black text-amber-600 mt-1 block">{{ $pendingReviewsCount }}</span>
            <span class="text-[11px] text-amber-700 font-semibold">Tugas konten masuk</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs">
            <span class="text-xs font-bold text-slate-400 block uppercase">Kunjungan / Mentoring</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $mentoringCount }}</span>
            <span class="text-[11px] text-slate-500 font-semibold">Sesi lapangan</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs">
            <span class="text-xs font-bold text-slate-400 block uppercase">Perlu Perbaikan QC</span>
            <span class="text-2xl font-black text-rose-600 mt-1 block">{{ $attentionNeededCount }}</span>
            <span class="text-[11px] text-rose-700 font-semibold">Produk evaluasi</span>
        </div>
    </div>

    <!-- Main Grid: Kelompok Binaan & Submissions to Review -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Kelompok Binaan List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-extrabold text-slate-900">Kelompok Binaan yang Didampingi</h2>
                <a href="{{ route('facilitator.groups.index') }}" class="text-xs font-bold text-[#B88E3E] hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($groups as $grp)
                <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900">{{ $grp->name }}</h3>
                                <p class="text-xs text-[#8E6920] font-semibold">{{ $grp->business_name ?? 'Kelompok Usaha' }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Aktif
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ $grp->regency }}</p>
                        <p class="text-xs text-slate-700 font-medium mt-2">Komoditas: {{ $grp->commodity_summary ?? '-' }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">PIC: {{ $grp->pic_name ?? $grp->leader_name }}</span>
                        <a href="{{ route('facilitator.groups.show', $grp->id) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-[#1E4D2B] hover:text-white text-slate-700 text-xs font-bold transition-colors">
                            Buka Kelompok
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full p-6 text-center bg-slate-50 rounded-2xl text-xs text-slate-400">
                    Belum ada kelompok yang ditugaskan.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Right 1 Col: Kiriman Tugas Terbaru yang Perlu Dinilai -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-extrabold text-slate-900">Kiriman Tugas Terbaru</h2>
                <a href="{{ route('facilitator.reviews.index') }}" class="text-xs font-bold text-[#B88E3E] hover:underline">Kelola &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($submissions as $sub)
                <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-extrabold text-slate-900">{{ $sub->group->name ?? 'Kelompok' }}</span>
                        @if($sub->status === 'dikumpulkan')
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">Menunggu Review</span>
                        @elseif($sub->status === 'disetujui')
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">Disetujui</span>
                        @else
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-rose-100 text-rose-800">{{ ucfirst($sub->status) }}</span>
                        @endif
                    </div>

                    <p class="text-xs font-bold text-[#8E6920]">{{ $sub->task->title ?? 'Tugas Konten' }}</p>

                    @if($sub->photo_url)
                    <img src="{{ $sub->photo_url }}" alt="Sub" class="w-full h-28 object-cover rounded-xl border border-slate-100">
                    @endif

                    <p class="text-xs text-slate-600 italic line-clamp-2">"{{ $sub->caption }}"</p>

                    <div class="pt-1">
                        <a href="{{ route('facilitator.reviews.index') }}" class="block text-center py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs">
                            Beri Feedback / Nilai
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center bg-slate-50 rounded-2xl text-xs text-slate-400">
                    Belum ada kiriman tugas terbaru.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

