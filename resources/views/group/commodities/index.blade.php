@extends('group.layouts.app')

@section('title', 'Komoditas Usaha Kelompok — RUMA')

@section('content')
<div class="space-y-4">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#1E4D2B]">Potensi Hasil Alam</span>
            <h2 class="text-lg font-black text-slate-900">Komoditas Kelompok</h2>
        </div>
        <button onclick="document.getElementById('modal-add-commodity').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl bg-[#1E4D2B] hover:bg-[#143D24] text-white font-bold text-xs shadow-xs flex items-center gap-1 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah
        </button>
    </div>

    <!-- Sub Navigation for Usaha (Using Clean SVG Icons) -->
    <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl overflow-x-auto hide-scrollbar text-xs font-bold">
        <a href="{{ route('group.land.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            <span>Lahan</span>
        </a>
        <a href="{{ route('group.commodities.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl bg-white text-slate-900 shadow-xs whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-[#B88E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            <span>Komoditas</span>
        </a>
        <a href="{{ route('group.finance.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Keuangan</span>
        </a>
        <a href="{{ route('group.products.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span>Produk & QC</span>
        </a>
    </div>

    <!-- Commodities Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @forelse($commodities as $item)
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden flex flex-col justify-between">
            <div>
                <div class="h-32 bg-slate-100 relative">
                    <img src="{{ $item->photo_url ?? 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=500&q=80' }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-bold bg-[#1E4D2B] text-white">
                        {{ $item->category }}
                    </div>
                    <div class="absolute top-2 right-2 px-2 py-0.5 rounded text-[10px] font-bold bg-white/90 text-slate-800 shadow-xs">
                        {{ $item->status }}
                    </div>
                </div>

                <div class="p-3.5 space-y-1.5">
                    <h4 class="text-sm font-extrabold text-slate-900">{{ $item->name }}</h4>
                    <p class="text-xs text-slate-500">Lahan: <b>{{ $item->land->name ?? 'Belum terhubung' }}</b></p>
                    @if($item->harvest_estimate)
                    <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-200/50 text-xs text-[#8E6920] font-semibold">
                        Estimasi Panen: {{ $item->harvest_estimate }}
                    </div>
                    @endif
                </div>
            </div>

            @if($item->planted_area_m2)
            <div class="p-3.5 pt-0 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Luas Tanam:</span>
                <span class="font-bold text-slate-800">{{ number_format($item->planted_area_m2, 0, ',', '.') }} m²</span>
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-500">
            Belum ada komoditas yang dicatat.
        </div>
        @endforelse
    </div>

    <!-- Modal Add Commodity -->
    <div id="modal-add-commodity" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-5 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900">Tambah Komoditas Baru</h3>
                <button type="button" onclick="document.getElementById('modal-add-commodity').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('group.commodities.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Komoditas *</label>
                    <input type="text" name="name" required placeholder="Contoh: Cabai Rawit Merah, Gula Aren" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1E4D2B]">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori *</label>
                        <select name="category" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1E4D2B]">
                            <option value="Hortikultura">Hortikultura</option>
                            <option value="Pertanian">Pertanian</option>
                            <option value="Perkebunan">Perkebunan</option>
                            <option value="Olahan Pangan">Olahan Pangan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Tanaman *</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1E4D2B]">
                            <option value="Aktif">Aktif</option>
                            <option value="Perawatan">Perawatan</option>
                            <option value="Siap Panen">Siap Panen</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Terhubung ke Lahan</label>
                    <select name="land_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1E4D2B]">
                        <option value="">-- Pilih Lahan (Opsional) --</option>
                        @foreach($lands as $l)
                        <option value="{{ $l->id }}">{{ $l->name }} ({{ $l->area_m2 }} m²)</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Estimasi Hasil Panen</label>
                        <input type="text" name="harvest_estimate" placeholder="Contoh: 250 kg / siklus" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Luas Ditanam (m²)</label>
                        <input type="number" name="planted_area_m2" placeholder="1000" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Catatan siklus bibit atau varietas..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-commodity').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1E4D2B] text-white font-bold hover:bg-[#143D24]">Simpan Komoditas</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

