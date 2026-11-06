@extends('layouts.app')

@section('title', $group->name . ' — Profil Kelompok Binaan | SiYota.org')

@section('content')
<div class="space-y-8 pb-16 max-w-4xl mx-auto">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('public.home') }}" class="hover:text-[#B88E3E]">Beranda</a>
        <span>/</span>
        <span class="text-slate-800">Kelompok Binaan</span>
    </div>

    <!-- Group Header Card -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="relative h-64 sm:h-72 w-full bg-slate-900">
            <img src="{{ $group->banner_url ?? 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $group->name }}" class="w-full h-full object-cover opacity-85">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            
            <div class="absolute bottom-6 left-6 right-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 text-white">
                <div class="space-y-1">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#1E4D2B] text-white">
                        Kelompok Binaan SiYota
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-black">{{ $group->name }}</h1>
                    <p class="text-sm text-amber-200 font-medium flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $group->village ? $group->village . ', ' : '' }}{{ $group->district ? $group->district . ', ' : '' }}{{ $group->regency }}
                    </p>
                </div>

                @if($group->business_name)
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/20 text-right">
                    <span class="text-[11px] text-amber-200 font-bold block uppercase tracking-wide">Merek Usaha</span>
                    <span class="text-base font-extrabold">{{ $group->business_name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-3 gap-6 border-b border-slate-100">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Komoditas Utama</span>
                <p class="text-sm font-bold text-slate-800">{{ $group->commodity_summary ?? 'Hasil Bumi Unggulan' }}</p>
            </div>
            @if($group->pic_public_visible && $group->pic_name)
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">PIC / Koordinator</span>
                <p class="text-sm font-bold text-slate-800">{{ $group->pic_name }}</p>
            </div>
            @endif
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Pendamping Program</span>
                <p class="text-sm font-bold text-[#1E4D2B]">SiYota.org Foundation</p>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-3">
            <h3 class="text-sm font-extrabold uppercase tracking-wide text-slate-900">Tentang Kelompok</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
                {{ $group->public_description ?? 'Kelompok masyarakat yang bergerak dalam pengelolaan pertanian, perkebunan, dan hilirisasi produk hasil bumi bernilai tambah tinggi di pedesaan.' }}
            </p>
        </div>
    </div>

    <!-- Public Products Catalog -->
    @if($group->products->count() > 0)
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-lg font-bold text-slate-900">Produk Hasil Kelompok</h2>
            <span class="text-xs text-slate-500 font-medium">{{ $group->products->count() }} produk terverifikasi</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($group->products as $product)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col justify-between space-y-3">
                <div class="h-40 rounded-xl bg-slate-100 overflow-hidden">
                    <img src="{{ $product->photo_url ?? 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=500&q=80' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">{{ $product->name }}</h4>
                    <p class="text-xs text-slate-500 mt-1">{{ $product->packaging_type ?? 'Kemasan Standar' }}</p>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <span class="text-sm font-black text-[#B88E3E]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="text-xs text-slate-400 font-medium">/ {{ $product->unit }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Public Lands -->
    @if($group->lands->count() > 0)
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-lg font-bold text-slate-900">Lahan Kelolaan Terbuka</h2>
            <span class="text-xs text-slate-500 font-medium">{{ $group->lands->count() }} titik lahan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($group->lands as $land)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-emerald-50 border border-emerald-200 flex flex-col items-center justify-center text-emerald-800 font-bold text-xs shrink-0">
                    <span>{{ round($land->area_m2 / 1000, 1) }}</span>
                    <span class="text-[10px] font-medium text-emerald-600">rb m²</span>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">{{ $land->name }}</h4>
                    <p class="text-xs text-slate-500">Status: {{ $land->land_status }}</p>
                    <p class="text-xs text-[#1E4D2B] font-semibold mt-1">Komoditas: {{ $land->commodities ?? '-' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

