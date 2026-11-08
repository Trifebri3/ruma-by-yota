@extends('admin.layouts.app')

@section('title', 'Tautan Laporan Progress Harian — Super Admin')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-[#B88E3E] text-white">
                    Tautan Publik
                </span>
                <span class="text-xs text-slate-400 font-medium">Daily Progress Report</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                Tautan Laporan Harian (Tanpa Login)
            </h1>
            <p class="text-xs text-slate-500">
                Kelola tautan publik untuk kelompok binaan dengan batas kuota laporan harian (WIB).
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-colors flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Lihat Laporan Masuk</span>
            </a>
            <button type="button" onclick="openCreateLinkModal()" class="px-4 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs shadow-md shadow-slate-900/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Buat Tautan Laporan</span>
            </button>
        </div>
    </div>

    <!-- Quick Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Tautan Dibuat</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-slate-900">{{ $stats['total_links'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">tautan</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-emerald-200/80 bg-emerald-50/20 shadow-xs">
            <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider block">Tautan Aktif</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-emerald-700">{{ $stats['active_links'] }}</span>
                <span class="text-[11px] text-emerald-600 font-medium">menerima laporan</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-rose-200/80 bg-rose-50/20 shadow-xs">
            <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider block">Tautan Ditutup</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-rose-700">{{ $stats['inactive_links'] }}</span>
                <span class="text-[11px] text-rose-600 font-medium">nonaktif</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.report_links.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Cari Judul / Kelompok</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Status Tautan</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Kelompok</label>
                <div class="flex gap-2">
                    <select name="group_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                        <option value="">Semua Kelompok</option>
                        @foreach($groups as $grp)
                            <option value="{{ $grp->id }}" {{ request('group_id') == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Links List Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($links as $link)
            @php
                $todayCount = $link->todayReportsCount($today);
                $isFull = $link->hasReachedDailyLimit($today);
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200/80 p-5 space-y-4 shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between">
                <div class="space-y-3">
                    <!-- Top Badge & Status -->
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block tracking-wider">Project / Kelompok</span>
                            <h3 class="text-sm font-extrabold text-slate-900">{{ $link->group->name }}</h3>
                            <span class="text-[11px] text-slate-500 font-medium">{{ $link->group->regency }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.report_links.toggle', $link->id) }}">
                                @csrf
                                @if($link->is_active)
                                    <button type="submit" title="Klik untuk menonaktifkan tautan" class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition-colors">
                                        ✓ AKTIF
                                    </button>
                                @else
                                    <button type="submit" title="Klik untuk mengaktifkan tautan" class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-800 hover:bg-rose-200 transition-colors">
                                        ✕ NONAKTIF
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Judul Laporan -->
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <div class="text-xs font-bold text-slate-800">{{ $link->title }}</div>
                        @if($link->description)
                            <div class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">{{ $link->description }}</div>
                        @endif
                    </div>

                    <!-- Batas Laporan & Kuota Hari Ini -->
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2.5 rounded-xl border border-slate-100 bg-white">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Batas Harian</span>
                            <span class="font-extrabold text-slate-900">
                                @if($link->daily_limit > 0)
                                    {{ $link->daily_limit }} laporan / hari
                                @else
                                    <span class="text-slate-600">Tidak Dibatasi</span>
                                @endif
                            </span>
                        </div>
                        <div class="p-2.5 rounded-xl border border-slate-100 {{ $isFull ? 'bg-amber-50/60 border-amber-200 text-amber-900' : 'bg-white' }}">
                            <span class="text-[10px] font-bold uppercase {{ $isFull ? 'text-amber-700' : 'text-slate-400' }} block">Laporan Hari Ini (WIB)</span>
                            <div class="flex items-center gap-1.5 font-extrabold">
                                <span>{{ $todayCount }} terkirim</span>
                                @if($isFull)
                                    <span class="text-[10px] px-1.5 py-0.2 rounded-md bg-amber-200 text-amber-900 font-black">Penuh</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- URL Publik Box dengan Salin Tautan -->
                    <div class="pt-1">
                        <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Tautan Publik (Bisa Diakses Tanpa Login)</span>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $link->public_url }}" id="url_{{ $link->id }}" class="flex-1 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 font-mono select-all focus:outline-none">
                            <button type="button" onclick="copyLink('{{ $link->public_url }}', this)" class="px-3 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all flex items-center gap-1.5 flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span>Salin</span>
                            </button>
                            <a href="{{ $link->public_url }}" target="_blank" class="p-2 rounded-xl border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors" title="Buka Halaman Form">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer Aksi -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <a href="{{ route('admin.reports.index', ['group_id' => $link->group_id]) }}" class="font-bold text-[#B88E3E] hover:underline flex items-center gap-1">
                        <span>Lihat Laporan Kelompok Ini &rarr;</span>
                    </a>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick='openEditLinkModal(@json($link))' class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors" title="Edit Tautan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.report_links.destroy', $link->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tautan ini? (Laporan yang sudah masuk tetap tersimpan di database)');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Tautan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl border border-slate-200/80 p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Belum ada tautan laporan</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                    Buat tautan laporan harian pertama untuk kelompok tani agar mereka dapat mengirimkan progress harian secara praktis tanpa harus login.
                </p>
                <button type="button" onclick="openCreateLinkModal()" class="px-4 py-2 rounded-2xl bg-slate-900 text-white font-extrabold text-xs">
                    + Buat Tautan Laporan Sekarang
                </button>
            </div>
        @endforelse
    </div>

    @if($links->hasPages())
        <div class="p-4 bg-white rounded-3xl border border-slate-200/80 shadow-xs">
            {{ $links->links() }}
        </div>
    @endif

</div>

<!-- Modal Buat Tautan Laporan -->
<div id="createLinkModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-black text-slate-900">Buat Tautan Laporan Baru</h3>
                <p class="text-xs text-slate-500">Tautan dapat langsung dibagikan kepada anggota kelompok.</p>
            </div>
            <button type="button" onclick="closeCreateLinkModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.report_links.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Kelompok / Project <span class="text-rose-500">*</span></label>
                <select name="group_id" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    <option value="">— Pilih Kelompok Dampingan —</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}" {{ request('group_id') == $grp->id ? 'selected' : '' }}>
                            {{ $grp->name }} ({{ $grp->regency }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Laporan <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: Laporan Harian — KWT Sumber Rejeki" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Instruksi / Deskripsi Laporan</label>
                <textarea name="description" rows="2" placeholder="Contoh: Unggah kegiatan harian, foto lahan/produk, dan kendala yang dihadapi di lapangan..." class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Batas Laporan Per Hari <span class="text-rose-500">*</span></label>
                    <select name="daily_limit" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                        <option value="1">1 laporan per hari</option>
                        <option value="2" selected>2 laporan per hari</option>
                        <option value="3">3 laporan per hari</option>
                        <option value="5">5 laporan per hari</option>
                        <option value="0">Tidak dibatasi</option>
                    </select>
                    <span class="text-[10px] text-slate-400 mt-1 block">Dihitung otomatis per tanggal (WIB)</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Tautan</label>
                    <select name="is_active" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                        <option value="1" selected>Aktif (Bisa Diisi)</option>
                        <option value="0">Nonaktif (Ditutup Sementara)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Mulai (Opsional)</label>
                    <input type="date" name="start_date" class="w-full px-3.5 py-2 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Berakhir (Opsional)</label>
                    <input type="date" name="end_date" class="w-full px-3.5 py-2 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <div class="pt-3 flex gap-2">
                <button type="button" onclick="closeCreateLinkModal()" class="flex-1 py-2.5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs shadow-md shadow-slate-900/20">
                    Simpan & Buat Tautan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Tautan Laporan -->
<div id="editLinkModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-black text-slate-900">Perbarui Pengaturan Tautan</h3>
                <p class="text-xs text-slate-500" id="edit_link_subtitle">Ubah batas laporan harian atau status tautan.</p>
            </div>
            <button type="button" onclick="closeEditLinkModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center">✕</button>
        </div>

        <form id="editLinkForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Kelompok / Project <span class="text-rose-500">*</span></label>
                <select id="edit_link_group_id" name="group_id" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}">{{ $grp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Laporan <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_link_title" name="title" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Instruksi / Deskripsi Laporan</label>
                <textarea id="edit_link_description" name="description" rows="2" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Batas Laporan Per Hari <span class="text-rose-500">*</span></label>
                    <select id="edit_link_daily_limit" name="daily_limit" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                        <option value="1">1 laporan per hari</option>
                        <option value="2">2 laporan per hari</option>
                        <option value="3">3 laporan per hari</option>
                        <option value="5">5 laporan per hari</option>
                        <option value="0">Tidak dibatasi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Tautan</label>
                    <select id="edit_link_is_active" name="is_active" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Mulai</label>
                    <input type="date" id="edit_link_start_date" name="start_date" class="w-full px-3.5 py-2 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Berakhir</label>
                    <input type="date" id="edit_link_end_date" name="end_date" class="w-full px-3.5 py-2 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <div class="pt-3 flex gap-2">
                <button type="button" onclick="closeEditLinkModal()" class="flex-1 py-2.5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateLinkModal() {
        document.getElementById('createLinkModal').classList.remove('hidden');
        document.getElementById('createLinkModal').classList.add('flex');
    }
    function closeCreateLinkModal() {
        document.getElementById('createLinkModal').classList.add('hidden');
        document.getElementById('createLinkModal').classList.remove('flex');
    }

    function openEditLinkModal(link) {
        document.getElementById('editLinkForm').action = '/admin/tautan-laporan/' + link.id;
        document.getElementById('edit_link_group_id').value = link.group_id;
        document.getElementById('edit_link_title').value = link.title || '';
        document.getElementById('edit_link_description').value = link.description || '';
        document.getElementById('edit_link_daily_limit').value = link.daily_limit;
        document.getElementById('edit_link_is_active').value = link.is_active ? '1' : '0';
        document.getElementById('edit_link_start_date').value = link.start_date ? link.start_date.substring(0, 10) : '';
        document.getElementById('edit_link_end_date').value = link.end_date ? link.end_date.substring(0, 10) : '';
        document.getElementById('edit_link_subtitle').innerText = 'Mengedit: ' + link.title;

        document.getElementById('editLinkModal').classList.remove('hidden');
        document.getElementById('editLinkModal').classList.add('flex');
    }
    function closeEditLinkModal() {
        document.getElementById('editLinkModal').classList.add('hidden');
        document.getElementById('editLinkModal').classList.remove('flex');
    }

    function copyLink(text, buttonElement) {
        navigator.clipboard.writeText(text).then(function() {
            const originalHtml = buttonElement.innerHTML;
            buttonElement.innerHTML = '<span>✓ Disalin!</span>';
            buttonElement.classList.add('bg-emerald-600');
            setTimeout(function() {
                buttonElement.innerHTML = originalHtml;
                buttonElement.classList.remove('bg-emerald-600');
            }, 2000);
        }).catch(function() {
            prompt('Salin tautan ini secara manual:', text);
        });
    }
</script>
@endpush
@endsection

