@extends('facilitator.layouts.app')

@section('title', 'Daftar Kelompok Binaan — Fasilitator SiYota')

@section('content')
<div class="space-y-6 pb-16">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#1E4D2B]">Pendampingan Lapangan</span>
            <h1 class="text-2xl font-black text-slate-900">Kelompok Binaan Anda</h1>
        </div>
        <a href="{{ route('facilitator.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Kembali ke Dashboard</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($groups as $grp)
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="h-36 bg-slate-100 relative">
                <img src="{{ $grp->banner_url ?? 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $grp->name }}" class="w-full h-full object-cover">
                <div class="absolute top-3 left-3 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#1E4D2B] text-white">
                    Kelompok Binaan
                </div>
            </div>

            <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">{{ $grp->name }}</h3>
                    <p class="text-xs font-semibold text-[#8E6920]">{{ $grp->business_name ?? 'Merek Usaha Komunitas' }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $grp->village ? $grp->village . ', ' : '' }}{{ $grp->regency }}</p>

                    <div class="pt-2 text-xs space-y-1 text-slate-600">
                        <div><b>Komoditas:</b> {{ $grp->commodity_summary ?? '-' }}</div>
                        <div><b>Kontak PIC:</b> {{ $grp->contact_phone }} ({{ $grp->leader_name }})</div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $grp->contact_phone) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition-colors">
                        WhatsApp
                    </a>
                    <a href="{{ route('facilitator.groups.show', $grp->id) }}" class="flex-1 text-center px-3 py-1.5 rounded-xl bg-[#1E4D2B] text-white text-xs font-bold hover:bg-[#143D24] transition-colors">
                        Buka Monitoring
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full p-8 text-center bg-slate-50 rounded-2xl text-sm text-slate-500">
            Belum ada kelompok yang terhubung dengan akun fasilitator Anda.
        </div>
        @endforelse
    </div>
</div>
@endsection

