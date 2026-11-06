@extends('admin.layouts.app')

@section('title', 'Manajemen Kelompok Binaan — Super Admin')

@section('content')
<div class="space-y-6 pb-16">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Master Data</span>
            <h1 class="text-2xl font-black text-slate-900">Kelompok Tani & Usaha Binaan</h1>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="document.getElementById('modal-add-group').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#1E4D2B] hover:bg-[#143D24] text-white font-bold text-xs flex items-center gap-1.5 shadow-xs">
                + Tambah Kelompok
            </button>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Dashboard</a>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex items-center gap-2 text-xs font-bold">
        <a href="{{ route('admin.groups.index') }}" class="px-3.5 py-1.5 rounded-xl {{ $status === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600' }}">
            Semua ({{ $groups->count() }})
        </a>
        <a href="{{ route('admin.groups.index', ['status' => 'active']) }}" class="px-3.5 py-1.5 rounded-xl {{ $status === 'active' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600' }}">
            Aktif
        </a>
        <a href="{{ route('admin.groups.index', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-xl {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600' }}">
            Menunggu Verifikasi
        </a>
    </div>

    <!-- Groups Grid Table -->
    <div class="space-y-4">
        @forelse($groups as $grp)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <img src="{{ $grp->banner_url ?? 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=200&q=80' }}" alt="{{ $grp->name }}" class="w-16 h-16 rounded-2xl object-cover shrink-0 border border-slate-200">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-extrabold text-slate-900">{{ $grp->name }}</h3>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold {{ $grp->status === 'active' ? 'bg-emerald-100 text-emerald-800' : ($grp->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                            {{ strtoupper($grp->status) }}
                        </span>
                        @if($grp->is_public)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                            Web Publik
                        </span>
                        @endif
                    </div>
                    <p class="text-xs text-[#8E6920] font-semibold">{{ $grp->business_name ?? 'Usaha Belum Diberi Merek' }} • {{ $grp->regency }}</p>
                    <p class="text-xs text-slate-500">PIC: <b>{{ $grp->pic_name ?? $grp->leader_name }}</b> ({{ $grp->contact_phone }})</p>
                    <p class="text-[11px] text-slate-600">Komoditas: {{ $grp->commodity_summary ?? '-' }}</p>
                </div>
            </div>

            <!-- Facilitator & Program Assignment Form -->
            <form action="{{ route('admin.groups.assign_fasil', $grp->id) }}" method="POST" class="bg-slate-50 p-3 rounded-xl flex flex-wrap items-center gap-2 text-xs border border-slate-200/60">
                @csrf
                <div>
                    <label class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Fasilitator:</label>
                    <select name="facilitator_id" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white font-medium">
                        <option value="">-- Tanpa Fasilitator --</option>
                        @foreach($facilitators as $f)
                        <option value="{{ $f->id }}" {{ $grp->facilitator_id == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Program:</label>
                    <select name="program_id" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white font-medium">
                        <option value="">-- Pilih Program --</option>
                        @foreach($programs as $pr)
                        <option value="{{ $pr->id }}" {{ $grp->program_id == $pr->id ? 'selected' : '' }}>{{ $pr->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-3">
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#B88E3E] text-white font-bold hover:bg-[#9E752D]">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
        @empty
        <div class="p-8 text-center bg-slate-50 rounded-2xl text-xs text-slate-500">
            Tidak ada kelompok yang ditemukan.
        </div>
        @endforelse
    </div>

    <!-- Modal Add Group -->
    <div id="modal-add-group" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900">Tambah Kelompok Tani Baru</h3>
                <button type="button" onclick="document.getElementById('modal-add-group').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('admin.groups.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kelompok *</label>
                    <input type="text" name="name" required placeholder="Contoh: KWT Mekar Wangi" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kabupaten / Wilayah *</label>
                        <input type="text" name="regency" required placeholder="Kabupaten Tasikmalaya" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Ketua / PIC *</label>
                        <input type="text" name="leader_name" required placeholder="Nama Ketua" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. WhatsApp PIC *</label>
                        <input type="text" name="contact_phone" required placeholder="08123456789" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Ringkasan Komoditas</label>
                        <input type="text" name="commodity_summary" placeholder="Cabai, Gula Aren, Sayuran" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tugaskan Fasilitator</label>
                        <select name="facilitator_id" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                            <option value="">-- Pilih Fasilitator --</option>
                            @foreach($facilitators as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Program</label>
                        <select name="program_id" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                            <option value="">-- Pilih Program --</option>
                            @foreach($programs as $pr)
                            <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-group').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1E4D2B] text-white font-bold hover:bg-[#143D24]">Simpan Kelompok</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

