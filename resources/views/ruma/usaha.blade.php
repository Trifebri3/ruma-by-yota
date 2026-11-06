@extends('group.layouts.app')

@section('title', 'Manajemen Usaha & Sumber Daya — RUMA')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold tracking-wider uppercase text-[#B88E3E]">MANAJEMEN SUMBER DAYA & USAHA</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                Kelola Usaha Kelompok
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pencatatan keuangan kas sederhana, sebaran lahan pada peta, inventaris komoditas, dan evaluasi Quality Control produk.
            </p>
        </div>

        <!-- Tab Switcher -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl overflow-x-auto max-w-full">
            <a href="{{ route('ruma.usaha', ['tab' => 'keuangan']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'keuangan' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Keuangan Kas
            </a>
            <a href="{{ route('ruma.usaha', ['tab' => 'produk']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'produk' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Produk & QC
            </a>
            <a href="{{ route('ruma.usaha', ['tab' => 'lahan']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'lahan' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Lahan & Peta
            </a>
            <a href="{{ route('ruma.usaha', ['tab' => 'komoditas']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'komoditas' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Komoditas
            </a>
        </div>
    </div>

    <!-- ==================== SUB-TAB 1: KEUANGAN KELOMPOK ==================== -->
    @if($tab === 'keuangan')
        <div class="space-y-6">
            
            <!-- Banner Saldo Sesuai Brief (Rp2.450.000 | Masuk Rp3.500.000 | Keluar Rp1.050.000) -->
            <div class="rounded-3xl bg-gradient-to-br from-[#FAF3E0] via-white to-amber-50/50 border border-[#E6CE93]/70 p-6 sm:p-7 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#B88E3E]">SALDO KELOMPOK</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-white text-slate-600 border border-slate-200">Kas Bersama (Bukan Pribadi)</span>
                        </div>
                        <div class="text-3xl sm:text-4xl font-black text-slate-900 mt-2 tracking-tight">
                            Rp{{ number_format($totalBalance, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Saldo dihitung otomatis dari total uang masuk dikurangi uang keluar kelompok.
                        </p>
                    </div>

                    <!-- Ringkasan Uang Masuk & Keluar -->
                    <div class="flex items-center gap-4">
                        <div class="p-4 rounded-2xl bg-white border border-slate-100 shadow-2xs min-w-[130px]">
                            <div class="text-[11px] font-bold text-slate-400 uppercase">Uang Masuk</div>
                            <div class="text-lg font-black text-emerald-700 mt-1">
                                Rp{{ number_format($totalIncome, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-white border border-slate-100 shadow-2xs min-w-[130px]">
                            <div class="text-[11px] font-bold text-slate-400 uppercase">Uang Keluar</div>
                            <div class="text-lg font-black text-rose-600 mt-1">
                                Rp{{ number_format($totalExpense, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Button Trigger Modal Catat Kas -->
                        <button type="button" onclick="document.getElementById('modal-catat-kas').classList.remove('hidden')" class="px-5 py-4 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#B88E3E]/20 transition-all flex items-center gap-2 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            <span>Catat Kas</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Daftar Transaksi Terakhir -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900">Riwayat Mutasi Kas Kelompok</h3>
                    <span class="text-xs text-slate-400">{{ $transactions->count() }} Transaksi Tercatat</span>
                </div>

                <div class="ruma-card rounded-3xl overflow-hidden divide-y divide-slate-100">
                    @forelse($transactions as $t)
                        <div class="p-4 sm:p-5 flex items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-2xl {{ $t->type === 'masuk' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} flex items-center justify-center font-bold text-base flex-shrink-0">
                                    {{ $t->type === 'masuk' ? '+' : '−' }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-900 text-sm truncate">{{ $t->category }}</h4>
                                        @if($t->commodity)
                                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                                {{ $t->commodity->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5 truncate">
                                        {{ $t->transaction_date->format('d F Y') }} • {{ $t->description }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <div class="text-sm sm:text-base font-extrabold {{ $t->type === 'masuk' ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $t->type === 'masuk' ? '+' : '−' }}Rp{{ number_format($t->amount, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    Dicatat: {{ $t->recordedBy->name ?? 'Ibu Rina' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-xs text-slate-400">
                            Belum ada riwayat transaksi dicatat.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- MODAL CATAT KAS -->
            <div id="modal-catat-kas" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-amber-100 text-[#B88E3E] flex items-center justify-center font-bold">
                                Rp
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-base">Catat Transaksi Keuangan</h3>
                        </div>
                        <button type="button" onclick="document.getElementById('modal-catat-kas').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('ruma.financial.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $group->id }}">

                        <!-- Jenis Transaksi (Masuk / Keluar) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Jenis Transaksi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center justify-center gap-2 p-3 rounded-2xl border border-slate-200 cursor-pointer has-checked:border-emerald-500 has-checked:bg-emerald-50/50 has-checked:text-emerald-800 transition-all">
                                    <input type="radio" name="type" value="masuk" checked class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-xs font-bold">Uang Masuk (+)</span>
                                </label>
                                <label class="flex items-center justify-center gap-2 p-3 rounded-2xl border border-slate-200 cursor-pointer has-checked:border-rose-500 has-checked:bg-rose-50/50 has-checked:text-rose-800 transition-all">
                                    <input type="radio" name="type" value="keluar" class="text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-bold">Uang Keluar (−)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Nominal & Tanggal -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nominal (Rp)</label>
                                <input type="number" name="amount" required placeholder="Contoh: 500000" min="1000" step="1000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:outline-none focus:border-[#B88E3E]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal</label>
                                <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:outline-none focus:border-[#B88E3E]">
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Transaksi</label>
                            <input type="text" name="category" required placeholder="Contoh: Penjualan cabai, Pembelian kemasan, Bibit" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                        </div>

                        <!-- Komoditas Terkait (Opsional) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Komoditas Terkait (Opsional)</label>
                            <select name="commodity_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:border-[#B88E3E]">
                                <option value="">-- Pilih Komoditas --</option>
                                @foreach($commodities as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan / Catatan</label>
                            <textarea name="description" rows="2" required placeholder="Catatan singkat transaksi kelompok..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]"></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="pt-2 flex items-center justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-catat-kas').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-xs shadow-xs">
                                Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    @endif

    <!-- ==================== SUB-TAB 2: PRODUK & QUALITY CONTROL (QC) ==================== -->
    @if($tab === 'produk')
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Katalog Produk Olahan & Evaluasi QC SiYota</h3>
                    <p class="text-xs text-slate-500">Pantau 4 pilar standar kualitas: Label Produk, Kemasan, Informasi Produk, dan Foto.</p>
                </div>
                <button type="button" onclick="document.getElementById('modal-tambah-produk').classList.remove('hidden')" class="px-4 py-2 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-xs shadow-xs flex items-center gap-1.5 self-start">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Produk Baru</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($products as $p)
                    <div class="ruma-card ruma-card-hover rounded-3xl overflow-hidden flex flex-col justify-between group">
                        <!-- Product Image -->
                        <div class="relative h-44 w-full overflow-hidden bg-slate-100">
                            <img src="{{ $p->photo_url }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Overall QC Status Badge -->
                            <div class="absolute top-3 right-3">
                                @if($p->status === 'sesuai_panduan')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-600 text-white shadow-xs">
                                        Sesuai Panduan
                                    </span>
                                @elseif($p->status === 'perlu_diperbaiki')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-rose-600 text-white shadow-xs animate-pulse">
                                        Perlu Perbaikan
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-500 text-white shadow-xs">
                                        Dalam Evaluasi
                                    </span>
                                @endif
                            </div>

                            @if($p->commodity)
                                <div class="absolute bottom-3 left-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-white/90 backdrop-blur-xs text-slate-800 shadow-2xs">
                                        Komoditas: {{ $p->commodity->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info & 4 QC Pillars -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="font-extrabold text-slate-900 text-base leading-snug">
                                        {{ $p->name }}
                                    </h4>
                                    <span class="font-black text-slate-900 text-sm whitespace-nowrap">
                                        Rp{{ number_format($p->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                    {{ $p->description }}
                                </p>
                            </div>

                            <!-- 4 Pilar Evaluasi QC -->
                            <div class="p-3 rounded-2xl bg-[#FDFBF7] border border-[#E6CE93]/50 space-y-2">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-[#B88E3E]">
                                    Evaluasi 4 Pilar QC SiYota
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <!-- 1. Label Produk -->
                                    <div class="flex items-center justify-between p-1.5 rounded-lg bg-white border border-slate-100">
                                        <span class="text-[11px] text-slate-600 font-medium">Label Produk</span>
                                        <span class="text-[10px] font-bold {{ $p->qc_label_status === 'sesuai' ? 'text-emerald-700' : ($p->qc_label_status === 'perbaikan' ? 'text-rose-600' : 'text-slate-400') }}">
                                            {{ ucfirst($p->qc_label_status) }}
                                        </span>
                                    </div>

                                    <!-- 2. Kemasan -->
                                    <div class="flex items-center justify-between p-1.5 rounded-lg bg-white border border-slate-100">
                                        <span class="text-[11px] text-slate-600 font-medium">Kemasan</span>
                                        <span class="text-[10px] font-bold {{ $p->qc_packaging_status === 'sesuai' ? 'text-emerald-700' : ($p->qc_packaging_status === 'perbaikan' ? 'text-rose-600' : 'text-slate-400') }}">
                                            {{ ucfirst($p->qc_packaging_status) }}
                                        </span>
                                    </div>

                                    <!-- 3. Informasi Produk -->
                                    <div class="flex items-center justify-between p-1.5 rounded-lg bg-white border border-slate-100">
                                        <span class="text-[11px] text-slate-600 font-medium">Info Produk</span>
                                        <span class="text-[10px] font-bold {{ $p->qc_info_status === 'sesuai' ? 'text-emerald-700' : ($p->qc_info_status === 'perbaikan' ? 'text-rose-600' : 'text-slate-400') }}">
                                            {{ ucfirst($p->qc_info_status) }}
                                        </span>
                                    </div>

                                    <!-- 4. Foto Produk -->
                                    <div class="flex items-center justify-between p-1.5 rounded-lg bg-white border border-slate-100">
                                        <span class="text-[11px] text-slate-600 font-medium">Foto Produk</span>
                                        <span class="text-[10px] font-bold {{ $p->qc_photo_status === 'sesuai' ? 'text-emerald-700' : ($p->qc_photo_status === 'perbaikan' ? 'text-rose-600' : 'text-slate-400') }}">
                                            {{ ucfirst($p->qc_photo_status) }}
                                        </span>
                                    </div>
                                </div>

                                @if($p->facilitator_notes)
                                    <div class="pt-1.5 border-t border-[#E6CE93]/40">
                                        <p class="text-[11px] text-slate-600 italic">
                                            "{{ $p->facilitator_notes }}"
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- MODAL TAMBAH PRODUK -->
            <div id="modal-tambah-produk" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-base">Daftarkan Produk Olahan Baru</h3>
                        </div>
                        <button type="button" onclick="document.getElementById('modal-tambah-produk').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('ruma.product.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $group->id }}">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Produk Olahan</label>
                            <input type="text" name="name" required placeholder="Contoh: Gula Semut Aren Tasik 250gr" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Komoditas Bahan Baku</label>
                                <select name="commodity_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:border-[#B88E3E]">
                                    <option value="">-- Pilih Komoditas --</option>
                                    @foreach($commodities as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kemasan</label>
                                <input type="text" name="packaging_type" placeholder="Pouch zipper, botol kaca, vacuum" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Jual (Rp)</label>
                                <input type="number" name="price" required placeholder="Contoh: 30000" min="0" step="1000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:outline-none focus:border-[#B88E3E]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Satuan Produk</label>
                                <input type="text" name="unit" required placeholder="Contoh: Pouch 250gr, Botol" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Singkat & Keunggulan</label>
                            <textarea name="description" rows="2" required placeholder="Ceritakan bahan alami, rasa khas, dan cara penyimpanan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-tambah-produk').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-xs shadow-xs">
                                Daftarkan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    @endif

    <!-- ==================== SUB-TAB 3: LAHAN & PETA INTERAKTIF ==================== -->
    @if($tab === 'lahan')
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Peta Sebaran Lahan & Potensi Wilayah</h3>
                    <p class="text-xs text-slate-500">Berbasis OpenStreetMap untuk memudahkan fasilitator memantau lokasi lahan binaan.</p>
                </div>
                <button type="button" onclick="document.getElementById('modal-tambah-lahan').classList.remove('hidden')" class="px-4 py-2 rounded-2xl bg-[#1E4D2B] hover:bg-[#15381F] text-white font-bold text-xs shadow-xs flex items-center gap-1.5 self-start">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Titik Lahan</span>
                </button>
            </div>

            <!-- Leaflet Interactive Map Container -->
            <div class="ruma-card rounded-3xl overflow-hidden p-2">
                <div id="land-map" class="w-full h-80 sm:h-96 rounded-2xl z-10"></div>
            </div>

            <!-- List of Lands -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($lands as $l)
                    <div class="ruma-card ruma-card-hover rounded-3xl p-5 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F8F2E4] text-[#B88E3E] border border-[#E6CE93]/60">
                                    {{ $l->land_status }}
                                </span>
                                <span class="text-xs font-extrabold text-slate-900">
                                    {{ number_format($l->area_m2, 0, ',', '.') }} m²
                                </span>
                            </div>

                            <h4 class="font-extrabold text-slate-900 text-base">
                                {{ $l->name }}
                            </h4>

                            <div class="text-xs text-slate-600 space-y-1">
                                <div><strong>Komoditas:</strong> {{ $l->commodities }}</div>
                                <div><strong>Pengelola:</strong> {{ $l->manager_name ?? 'Kelompok' }}</div>
                            </div>

                            @if($l->notes)
                                <p class="text-xs text-slate-500 italic bg-slate-50 p-2.5 rounded-xl">
                                    "{{ $l->notes }}"
                                </p>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span>Titik: {{ number_format($l->latitude, 4) }}, {{ number_format($l->longitude, 4) }}</span>
                            <button type="button" onclick="focusMap({{ $l->latitude }}, {{ $l->longitude }}, '{{ $l->name }}')" class="font-bold text-[#B88E3E] hover:underline">
                                Sorot di Peta
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- MODAL TAMBAH LAHAN -->
            <div id="modal-tambah-lahan" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#1E4D2B] flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-base">Tambah Titik Lahan Kelompok</h3>
                        </div>
                        <button type="button" onclick="document.getElementById('modal-tambah-lahan').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('ruma.land.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $group->id }}">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lahan</label>
                            <input type="text" name="name" required placeholder="Contoh: Lahan D - Blok Pasir Kaler" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Luas Lahan (m²)</label>
                                <input type="number" name="area_m2" required placeholder="Contoh: 2500" min="1" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Lahan</label>
                                <select name="land_status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:border-[#B88E3E]">
                                    <option value="Milik Kelompok">Milik Kelompok</option>
                                    <option value="Milik Anggota">Milik Anggota</option>
                                    <option value="Kas Desa (Bina Pinjam)">Kas Desa (Bina Pinjam)</option>
                                    <option value="Sewa Bersama">Sewa Bersama</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Latitude</label>
                                <input type="text" name="latitude" required value="-7.3482" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Longitude</label>
                                <input type="text" name="longitude" required value="108.1632" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Komoditas yang Ditanam</label>
                            <input type="text" name="commodities" placeholder="Contoh: Cabai rawit, tomat, jahe merah" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pengelola / Penanggung Jawab</label>
                            <input type="text" name="manager_name" placeholder="Nama anggota penanggung jawab" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Tambahan (Pengairan, dll.)</label>
                            <textarea name="notes" rows="2" placeholder="Kondisi tanah, sumber air, akses jalan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-tambah-lahan').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#1E4D2B] hover:bg-[#15381F] text-white font-bold text-xs shadow-xs">
                                Simpan Titik Lahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    @endif

    <!-- ==================== SUB-TAB 4: KOMODITAS ==================== -->
    @if($tab === 'komoditas')
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Daftar Komoditas Hasil Bumi Kelompok</h3>
                    <p class="text-xs text-slate-500">Pencatatan dasar komoditas: luasan tanam, estimasi panen, dan status budidaya.</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-50 text-[#1E4D2B] border border-emerald-200">
                    {{ $commodities->count() }} Komoditas Aktif
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($commodities as $com)
                    <div class="ruma-card ruma-card-hover rounded-3xl overflow-hidden flex flex-col justify-between group">
                        <div class="relative h-44 w-full overflow-hidden bg-slate-100">
                            <img src="{{ $com->photo_url }}" alt="{{ $com->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-white/95 text-slate-900 shadow-xs">
                                    {{ $com->category }}
                                </span>
                            </div>
                            <div class="absolute bottom-3 right-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-2xs">
                                    {{ $com->status }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <h4 class="font-extrabold text-slate-900 text-base leading-snug">
                                    {{ $com->name }}
                                </h4>
                                <div class="text-xs text-slate-600 space-y-1">
                                    <div><strong>Lokasi:</strong> {{ $com->land->name ?? 'Lahan Kelompok' }}</div>
                                    <div><strong>Estimasi Panen:</strong> {{ $com->harvest_estimate ?? 'Sesuai Siklus' }}</div>
                                    @if($com->planted_area_m2)
                                        <div><strong>Luas Tanam:</strong> {{ number_format($com->planted_area_m2, 0, ',', '.') }} m²</div>
                                    @endif
                                </div>
                                @if($com->notes)
                                    <p class="text-xs text-slate-500 italic bg-slate-50 p-2.5 rounded-xl">
                                        "{{ $com->notes }}"
                                    </p>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span>{{ $com->products->count() }} Produk Olahan</span>
                                <a href="{{ route('ruma.usaha', ['tab' => 'produk']) }}" class="font-bold text-[#B88E3E] hover:underline">
                                    Lihat Produk →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
@if($tab === 'lahan')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('land-map').setView([-7.349, 108.165], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var landsData = @json($lands);
        var markers = [];

        landsData.forEach(function (land) {
            var marker = L.marker([land.latitude, land.longitude]).addTo(map);
            marker.bindPopup(`
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; line-height: 1.4;">
                    <strong style="color: #111827; font-size: 13px;">${land.name}</strong><br>
                    <span style="color: #B88E3E; font-weight: bold;">${land.land_status}</span> • ${land.area_m2} m²<br>
                    <span style="color: #4B5563;">Komoditas: ${land.commodities || '-'}</span><br>
                    <span style="color: #6B7280; font-size: 11px;">Pengelola: ${land.manager_name || 'Kelompok'}</span>
                </div>
            `);
            markers.push(marker);
        });

        window.focusMap = function (lat, lng, name) {
            map.flyTo([lat, lng], 16, { duration: 1.2 });
            markers.forEach(function (m) {
                var pos = m.getLatLng();
                if (Math.abs(pos.lat - lat) < 0.0001 && Math.abs(pos.lng - lng) < 0.0001) {
                    m.openPopup();
                }
            });
        };
    });
</script>
@endif
@endpush

