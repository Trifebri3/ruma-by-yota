@extends('admin.layouts.app')

@section('title', 'Laporan Progress Harian Masuk — Super Admin')

@section('content')
<div class="space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-slate-900 text-white">
                    Monitoring Progress
                </span>
                <span class="text-xs text-slate-400 font-medium">Daily Progress Feed</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                Laporan Progress Harian Kelompok
            </h1>
            <p class="text-xs text-slate-500">
                Seluruh aktivitas, capaian harian, dan dokumentasi foto dari kelompok binaan secara real-time.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.report_links.index') }}" class="px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 font-extrabold text-xs hover:bg-slate-50 transition-colors flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                <span>Kelola Tautan Publik & Batas Harian</span>
            </a>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Laporan Masuk</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-slate-900">{{ $stats['total_reports'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">laporan</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-[#E6CE93]/80 bg-[#F8F2E4]/30 shadow-xs">
            <span class="text-[11px] font-bold text-[#8E6920] uppercase tracking-wider block">Laporan Hari Ini (WIB)</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-[#B88E3E]">{{ $stats['today_reports'] }}</span>
                <span class="text-[11px] text-[#8E6920] font-medium">masuk</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Kelompok Aktif Melapor</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-slate-900">{{ $stats['active_groups'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">kelompok</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-emerald-200/80 bg-emerald-50/20 shadow-xs">
            <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider block">Tautan Aktif Berjalan</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-emerald-700">{{ $stats['active_links'] }}</span>
                <span class="text-[11px] text-emerald-600 font-medium">tautan</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Cari Pelapor / Judul</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Filter Kelompok</label>
                <select name="group_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    <option value="">Semua Kelompok</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}" {{ request('group_id') == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Rentang Tanggal</label>
                <select name="date_filter" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    <option value="">Semua Tanggal</option>
                    <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Hari Ini (WIB)</option>
                    <option value="last_7_days" {{ request('date_filter') === 'last_7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="last_30_days" {{ request('date_filter') === 'last_30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors">
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['search', 'group_id', 'date_filter', 'date']))
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Feed Laporan Masuk -->
    <div class="space-y-4">
        @forelse($reports as $report)
            <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4 hover:border-slate-300 transition-all">
                
                <!-- Header Card: Kelompok, Pelapor, Tanggal, Status -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#F8F2E4] text-[#B88E3E] font-black flex items-center justify-center text-xs flex-shrink-0 border border-[#E6CE93]/60">
                            {{ strtoupper(substr($report->group->name ?? 'KP', 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-black text-slate-900 text-sm">{{ $report->group->name ?? 'Kelompok' }}</h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                    {{ $report->group->regency ?? 'Wilayah' }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium">
                                Pelapor: <strong class="text-slate-800">{{ $report->reporter_name }}</strong>
                                @if($report->user)
                                    <span class="text-[10px] px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 font-semibold">(Akun Terverifikasi)</span>
                                @else
                                    <span class="text-[10px] px-1.5 py-0.2 rounded bg-amber-50 text-amber-800 border border-amber-200 font-semibold">(Tautan Publik)</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:self-center self-start">
                        <!-- Tanggal & Jam -->
                        <div class="text-right sm:block hidden">
                            <span class="text-xs font-extrabold text-slate-800 block">{{ \Carbon\Carbon::parse($report->report_date)->locale('id')->isoFormat('D MMMM Y') }}</span>
                            <span class="text-[10px] text-slate-400">{{ $report->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                        </div>
                        <!-- Status Badge -->
                        @if($report->status === 'approved')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">DISETUJUI</span>
                        @elseif($report->status === 'reviewed')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-blue-100 text-blue-800">DITINJAU</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-700">TERKIRIM</span>
                        @endif
                    </div>
                </div>

                <!-- Konten Laporan -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="lg:col-span-2 space-y-2">
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">
                            {{ $report->link ? 'Melalui Tautan: ' . $report->link->title : 'Laporan Langsung dari Akun' }}
                        </div>
                        <h4 class="text-base font-black text-slate-900 leading-snug">
                            {{ $report->title }}
                        </h4>
                        <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                            {{ $report->description }}
                        </div>

                        <!-- Catatan Admin jika ada -->
                        @if($report->admin_notes)
                            <div class="p-3 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-xs text-amber-900">
                                <span class="font-bold text-[11px] uppercase tracking-wider block text-amber-800">Catatan Fasilitator / Admin:</span>
                                {{ $report->admin_notes }}
                            </div>
                        @endif
                    </div>

                    <!-- Foto Dokumentasi -->
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">Foto Dokumentasi Kegiatan</span>
                        @if($report->photo_url)
                            <div class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-video cursor-pointer" onclick="openPhotoModal('{{ asset($report->photo_url) }}', '{{ addslashes($report->title) }}')">
                                <img src="{{ asset($report->photo_url) }}" alt="Dokumentasi {{ $report->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                    <span>Lihat Foto Penuh</span>
                                </div>
                            </div>
                        @else
                            <div class="p-6 rounded-2xl border border-dashed border-slate-200 text-center text-slate-400 text-xs">
                                <svg class="w-6 h-6 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Tidak ada lampiran foto</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Aksi Admin: Verifikasi & Hapus -->
                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <form method="POST" action="{{ route('admin.reports.update_status', $report->id) }}" class="flex items-center gap-2">
                        @csrf
                        <label class="text-[11px] font-bold text-slate-500 uppercase">Ubah Status:</label>
                        <select name="status" onchange="this.form.submit()" class="px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 focus:outline-none focus:border-[#B88E3E]">
                            <option value="submitted" {{ $report->status === 'submitted' ? 'selected' : '' }}>Terkirim</option>
                            <option value="reviewed" {{ $report->status === 'reviewed' ? 'selected' : '' }}>Sudah Ditinjau</option>
                            <option value="approved" {{ $report->status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        </select>
                    </form>

                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.reports.destroy', $report->id) }}" onsubmit="return confirm('Hapus laporan progress ini?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors text-xs font-bold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Hapus Laporan</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Belum ada laporan masuk</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                    Laporan yang dikirimkan oleh kelompok melalui tautan publik atau dashboard akan tampil di sini secara terstruktur.
                </p>
            </div>
        @endforelse
    </div>

    @if($reports->hasPages())
        <div class="p-4 bg-white rounded-3xl border border-slate-200/80 shadow-xs">
            {{ $reports->links() }}
        </div>
    @endif

</div>

<!-- Modal Zoom Foto -->
<div id="photoModal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm hidden items-center justify-center p-4" onclick="closePhotoModal()">
    <div class="max-w-3xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl relative" onclick="event.stopPropagation()">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 id="photoModalTitle" class="text-xs font-bold line-clamp-1">Foto Dokumentasi Kegiatan</h4>
            <button type="button" onclick="closePhotoModal()" class="w-7 h-7 rounded-full bg-slate-800 text-white hover:bg-slate-700 flex items-center justify-center text-xs">✕</button>
        </div>
        <div class="bg-black flex items-center justify-center max-h-[80vh]">
            <img id="photoModalImg" src="" alt="Dokumentasi" class="max-w-full max-h-[80vh] object-contain">
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openPhotoModal(url, title) {
        document.getElementById('photoModalImg').src = url;
        document.getElementById('photoModalTitle').innerText = title || 'Dokumentasi Foto';
        document.getElementById('photoModal').classList.remove('hidden');
        document.getElementById('photoModal').classList.add('flex');
    }
    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.getElementById('photoModal').classList.remove('flex');
    }
</script>
@endpush
@endsection

