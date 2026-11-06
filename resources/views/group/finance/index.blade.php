@extends('group.layouts.app')

@section('title', 'Pencatatan Keuangan — RUMA')

@section('content')
<div class="space-y-4">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800">Buku Kas Sederhana</span>
            <h2 class="text-lg font-black text-slate-900">Keuangan Kelompok</h2>
        </div>
        <div class="flex items-center gap-1.5">
            <a href="{{ route('group.finance.index', ['view' => 'print']) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center gap-1.5" title="Cetak Rekap untuk Rapat">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Rekap</span>
            </a>
            <button onclick="document.getElementById('modal-add-finance').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-xs shadow-xs flex items-center gap-1">
                + Catat Kas
            </button>
        </div>
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
        <a href="{{ route('group.finance.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl bg-white text-slate-900 shadow-xs whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-[#B88E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Keuangan</span>
        </a>
        <a href="{{ route('group.products.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span>Produk & QC</span>
        </a>
    </div>

    <!-- Saldo Card -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-4 shadow-sm space-y-3">
        <div>
            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Sisa Kas Tersedia</span>
            <div class="text-2xl font-black mt-0.5">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-700/80 text-xs">
            <div>
                <span class="text-[10px] text-emerald-400 block font-semibold">Total Pemasukan</span>
                <span class="font-extrabold text-white">+Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-[10px] text-rose-400 block font-semibold">Total Pengeluaran</span>
                <span class="font-extrabold text-white">-Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Type Filter Tabs -->
    <div class="flex items-center gap-1 text-xs font-bold">
        <a href="{{ route('group.finance.index') }}" class="px-3 py-1.5 rounded-xl {{ !request('type') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600' }}">
            Semua
        </a>
        <a href="{{ route('group.finance.index', ['type' => 'masuk']) }}" class="px-3 py-1.5 rounded-xl {{ request('type') === 'masuk' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600' }}">
            Uang Masuk
        </a>
        <a href="{{ route('group.finance.index', ['type' => 'keluar']) }}" class="px-3 py-1.5 rounded-xl {{ request('type') === 'keluar' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600' }}">
            Uang Keluar
        </a>
    </div>

    <!-- Transaction List -->
    <div class="space-y-2">
        @forelse($records as $rec)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl {{ $rec->type === 'masuk' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }} flex items-center justify-center font-bold text-sm shrink-0">
                    {{ $rec->type === 'masuk' ? '↓' : '↑' }}
                </div>
                <div class="min-w-0">
                    <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase {{ $rec->type === 'masuk' ? 'bg-emerald-50 text-emerald-800' : 'bg-rose-50 text-rose-800' }}">
                        {{ $rec->category }}
                    </span>
                    <h5 class="text-xs font-extrabold text-slate-900 truncate mt-0.5">{{ $rec->description }}</h5>
                    <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($rec->transaction_date)->format('d M Y') }}</p>
                </div>
            </div>

            <div class="text-right shrink-0">
                <span class="text-xs font-black {{ $rec->type === 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $rec->type === 'masuk' ? '+' : '-' }}Rp {{ number_format($rec->amount, 0, ',', '.') }}
                </span>
                @if($rec->commodity)
                <span class="block text-[9px] text-slate-400 font-medium">{{ $rec->commodity->name }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-500">
            Belum ada transaksi tercatat.
        </div>
        @endforelse

        <div class="pt-2">
            {{ $records->links() }}
        </div>
    </div>

    <!-- Modal Add Finance Transaction -->
    <div id="modal-add-finance" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-5 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900">Catat Kas Baru</h3>
                <button type="button" onclick="document.getElementById('modal-add-finance').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('group.finance.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Kas *</label>
                        <select name="type" required class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
                            <option value="masuk">Uang Masuk (Penjualan / Hibah)</option>
                            <option value="keluar">Uang Keluar (Belanja / Operasional)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal *</label>
                        <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jumlah Nominal (Rp) *</label>
                    <input type="number" name="amount" required min="100" placeholder="500000" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-bold focus:outline-none focus:border-[#B88E3E]">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kategori Transaksi *</label>
                    <input type="text" name="category" required placeholder="Contoh: Penjualan Cabai, Beli Pupuk, Beli Kemasan Pouch" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keterangan Singkat *</label>
                    <input type="text" name="description" required placeholder="Contoh: Panen ke-3 seberat 40kg diserap pasar" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Terkait Komoditas (Opsional)</label>
                    <select name="commodity_id" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                        <option value="">-- Pilih Komoditas --</option>
                        @foreach($commodities as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-finance').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#B88E3E] text-white font-bold hover:bg-[#9E752D]">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

