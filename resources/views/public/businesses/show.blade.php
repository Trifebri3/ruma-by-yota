@extends('layouts.app')

@section('title', ($group->business_name ?? $group->name) . ' — Produk Unggulan Binaan SiYota')

@section('content')
<div class="space-y-8 pb-16 max-w-4xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('public.home') }}" class="hover:text-[#B88E3E]">Beranda</a>
        <span>/</span>
        <a href="{{ route('public.groups.show', $group->slug) }}" class="hover:text-[#B88E3E]">{{ $group->name }}</a>
        <span>/</span>
        <span class="text-slate-800">Usaha & Produk</span>
    </div>

    <!-- Business Hero Banner -->
    <div class="bg-gradient-to-br from-[#1E4D2B] to-[#143D24] text-white rounded-3xl p-6 sm:p-10 shadow-sm relative overflow-hidden">
        <div class="absolute right-0 bottom-0 opacity-10">
            <svg class="w-72 h-72 -mr-16 -mb-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>

        <div class="relative z-10 space-y-4 max-w-2xl">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#B88E3E] text-white">
                Merek Usaha Komunitas
            </span>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight">{{ $group->business_name ?? $group->name }}</h1>
            <p class="text-emerald-100 text-sm leading-relaxed">
                Diproduksi langsung oleh kelompok tani <strong>{{ $group->name }}</strong> di {{ $group->regency }}. Memadukan panen lokal segar dengan standar mutu pendampingan SiYota.org.
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-3">
                @if($group->contact_phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $group->contact_phone) }}?text=Halo%20{{ urlencode($group->name) }},%20saya%20tertarik%20dengan%20produk%20Anda." target="_blank" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow-md flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    Pesan via WhatsApp (PIC)
                </a>
                @endif
                <a href="{{ route('public.groups.show', $group->slug) }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all">
                    Lihat Profil Kelompok
                </a>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-slate-900">Daftar Produk Unggulan</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($group->products as $product)
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="h-48 bg-slate-100 overflow-hidden relative">
                        <img src="{{ $product->photo_url ?? 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        <div class="absolute top-2 right-2 px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#1E4D2B] text-white">
                            {{ $product->packaging_type ?? 'Siap Kirim' }}
                        </div>
                    </div>

                    <div class="p-4 space-y-2">
                        <h3 class="text-base font-bold text-slate-900">{{ $product->name }}</h3>
                        <p class="text-xs text-slate-600 line-clamp-2">{{ $product->description ?? 'Produk olahan higienis bersumber dari hasil bumi kelompok terpercaya.' }}</p>
                    </div>
                </div>

                <div class="p-4 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-400 block">Harga</span>
                        <span class="text-base font-extrabold text-[#B88E3E]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="text-xs text-slate-500">/ {{ $product->unit }}</span>
                    </div>

                    @if($group->contact_phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $group->contact_phone) }}?text=Halo,%20saya%20mau%20pesan%20{{ urlencode($product->name) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1 transition-colors">
                        Beli
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
                <p class="text-sm text-slate-500">Belum ada produk yang dipublikasikan dari usaha ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

