@extends('group.layouts.app')

@section('title', 'Produk & Quality Control — RUMA')

@section('content')
<div class="space-y-4">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#B88E3E]">Standar Mutu Produk</span>
            <h2 class="text-lg font-black text-slate-900">Produk & QC Kelompok</h2>
        </div>
        <button onclick="document.getElementById('modal-add-product').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-xs shadow-xs flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Daftarkan Produk
        </button>
    </div>

    <!-- Sub Navigation for Usaha (Using Clean SVG Icons) -->
    <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl overflow-x-auto hide-scrollbar text-xs font-bold">
        <a href="{{ route('group.land.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            <span>Lahan</span>
        </a>
        <a href="{{ route('group.commodities.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            <span>Komoditas</span>
        </a>
        <a href="{{ route('group.finance.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Keuangan</span>
        </a>
        <a href="{{ route('group.products.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl bg-white text-slate-900 shadow-xs whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-[#B88E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span>Produk & QC</span>
        </a>
    </div>

    <!-- Products List with 4 QC Items -->
    <div class="space-y-4">
        @forelse($products as $prod)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <img src="{{ $prod->photo_url ?? 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=200&q=80' }}" alt="{{ $prod->name }}" class="w-14 h-14 rounded-xl object-cover shrink-0 border border-slate-100">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h4 class="text-xs font-extrabold text-slate-900">{{ $prod->name }}</h4>
                            @if($prod->is_public)
                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Publik</span>
                            @endif
                        </div>
                        <p class="text-xs font-black text-[#B88E3E] mt-0.5">Rp {{ number_format($prod->price, 0, ',', '.') }} <span class="text-[10px] text-slate-400 font-normal">/ {{ $prod->unit }}</span></p>
                        <p class="text-[10px] text-slate-500">{{ $prod->packaging_type }} • {{ $prod->commodity->name ?? 'Komoditas Terpilih' }}</p>
                    </div>
                </div>

                <!-- Status Badge -->
                @if($prod->status === 'sesuai_panduan')
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 shrink-0">
                    Sesuai Standar
                </span>
                @elseif($prod->status === 'perlu_diperbaiki')
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 shrink-0">
                    Perlu Perbaikan
                </span>
                @else
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 shrink-0">
                    Dalam Evaluasi
                </span>
                @endif
            </div>

            <!-- 4-Pillar Quality Control Badges -->
            <div class="bg-slate-50 rounded-xl p-2.5 space-y-1.5 text-[11px]">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Evaluasi 4 Pilar QC:</span>
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-200/60">
                        <span class="text-slate-600">1. Label Produk:</span>
                        <span class="font-bold {{ $prod->qc_label_status === 'sesuai' ? 'text-emerald-600' : ($prod->qc_label_status === 'perbaikan' ? 'text-rose-600' : 'text-slate-400') }}">
                            {{ ucfirst($prod->qc_label_status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-200/60">
                        <span class="text-slate-600">2. Kemasan:</span>
                        <span class="font-bold {{ $prod->qc_packaging_status === 'sesuai' ? 'text-emerald-600' : ($prod->qc_packaging_status === 'perbaikan' ? 'text-rose-600' : 'text-slate-400') }}">
                            {{ ucfirst($prod->qc_packaging_status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-200/60">
                        <span class="text-slate-600">3. Info Produk:</span>
                        <span class="font-bold {{ $prod->qc_info_status === 'sesuai' ? 'text-emerald-600' : ($prod->qc_info_status === 'perbaikan' ? 'text-rose-600' : 'text-slate-400') }}">
                            {{ ucfirst($prod->qc_info_status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded-lg border border-slate-200/60">
                        <span class="text-slate-600">4. Foto Produk:</span>
                        <span class="font-bold {{ $prod->qc_photo_status === 'sesuai' ? 'text-emerald-600' : ($prod->qc_photo_status === 'perbaikan' ? 'text-rose-600' : 'text-slate-400') }}">
                            {{ ucfirst($prod->qc_photo_status) }}
                        </span>
                    </div>
                </div>
            </div>

            @if($prod->facilitator_notes)
            <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-200 text-xs text-amber-900 leading-snug">
                <b class="text-[#8E6920]">Catatan Fasilitator:</b> {{ $prod->facilitator_notes }}
            </div>
            @endif
        </div>
        @empty
        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-500">
            Belum ada produk yang didaftarkan.
        </div>
        @endforelse
    </div>

    <!-- Modal Add Product -->
    <div id="modal-add-product" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-5 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900">Daftarkan Produk untuk Evaluasi QC</h3>
                <button type="button" onclick="document.getElementById('modal-add-product').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('group.products.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Produk Olahan / Kemasan *</label>
                    <input type="text" name="name" required placeholder="Contoh: Bubuk Cabai Kering Super, Gula Aren Cetak" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Harga Satuan (Rp) *</label>
                        <input type="number" name="price" required placeholder="25000" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Satuan Kemasan *</label>
                        <input type="text" name="unit" required placeholder="Pcs, Botol 250ml, Pouch" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bahan Komoditas</label>
                        <select name="commodity_id" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                            <option value="">-- Pilih Komoditas --</option>
                            @foreach($commodities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Kemasan</label>
                        <input type="text" name="packaging_type" required placeholder="Standing Pouch, Botol Kaca" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keterangan Produk</label>
                    <textarea name="description" rows="2" placeholder="Komposisi, tanggal kedaluwarsa, izin P-IRT jika ada..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_public" id="is_public" value="1" checked class="rounded text-[#B88E3E] focus:ring-[#B88E3E]">
                    <label for="is_public" class="font-medium text-slate-700">Tampilkan ke Katalog Publik ruma.siyota.org</label>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-product').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#B88E3E] text-white font-bold hover:bg-[#9E752D]">Simpan & Masuk QC</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

