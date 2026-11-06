@extends('admin.layouts.app')

@section('title', 'Antrean Verifikasi Pendaftaran Kelompok — Super Admin')

@section('content')
<div class="space-y-6 pb-16">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Approval Queue</span>
            <h1 class="text-2xl font-black text-slate-900">Verifikasi Pendaftaran Kelompok</h1>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Dashboard</a>
    </div>

    <div class="space-y-4">
        @forelse($pendingGroups as $p)
        <div class="bg-white rounded-2xl border-2 border-amber-300 p-5 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                <div class="space-y-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-100 text-amber-800">
                        Pendaftaran Masuk: {{ $p->created_at->format('d M Y, H:i') }}
                    </span>
                    <h3 class="text-lg font-black text-slate-900">{{ $p->name }}</h3>
                    <p class="text-xs text-slate-600">Wilayah: <b>{{ $p->regency }}</b> ({{ $p->village ?? '-' }}, {{ $p->district ?? '-' }})</p>
                    <p class="text-xs text-slate-600">Ketua / PIC: <b>{{ $p->leader_name }}</b> • No. WA: <b class="text-emerald-700">{{ $p->contact_phone }}</b></p>
                    <p class="text-xs text-[#1E4D2B] font-bold">Komoditas: {{ $p->commodity_summary ?? '-' }}</p>
                </div>

                <!-- Approval Actions -->
                <div class="flex flex-col gap-2 shrink-0">
                    <form action="{{ route('admin.approvals.verify', $p->id) }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <input type="text" name="notes" placeholder="Catatan verifikasi..." class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
                        
                        <div class="flex items-center gap-2">
                            <button type="submit" name="action" value="active" class="flex-1 px-4 py-2 rounded-xl bg-[#1E4D2B] hover:bg-[#143D24] text-white font-extrabold text-xs shadow-xs transition-colors">
                                ✓ Setujui & Beri Akses
                            </button>
                            <button type="submit" name="action" value="rejected" class="px-3 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs transition-colors">
                                Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center bg-slate-50 rounded-2xl text-xs text-slate-500">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="font-bold text-slate-700">Semua pendaftaran telah diverifikasi!</p>
            <p class="text-slate-400 mt-0.5">Tidak ada antrean kelompok pending saat ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

