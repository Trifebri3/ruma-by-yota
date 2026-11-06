@extends('admin.layouts.app')

@section('title', 'Program Pendampingan — Super Admin')

@section('content')
<div class="space-y-6 pb-16">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Inisiatif SiYota</span>
            <h1 class="text-2xl font-black text-slate-900">Program Pendampingan Usaha</h1>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="document.getElementById('modal-add-program').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#1E4D2B] hover:bg-[#143D24] text-white font-bold text-xs flex items-center gap-1.5 shadow-xs">
                + Buat Program Baru
            </button>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Dashboard</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($programs as $pr)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex flex-col justify-between space-y-3">
            <div>
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                        {{ strtoupper($pr->status) }}
                    </span>
                    <span class="text-xs text-slate-500 font-bold">{{ $pr->groups_count }} Kelompok Terdaftar</span>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mt-2">{{ $pr->name }}</h3>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $pr->description ?? 'Program akselerasi komoditas dan mutu produk pedesaan.' }}</p>
            </div>

            <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
                <span>Periode: {{ $pr->start_date ? \Carbon\Carbon::parse($pr->start_date)->format('M Y') : 'Berjalan' }} - {{ $pr->end_date ? \Carbon\Carbon::parse($pr->end_date)->format('M Y') : 'Seterusnya' }}</span>
                <span class="font-bold text-[#B88E3E]">SiYota Foundation</span>
            </div>
        </div>
        @empty
        <div class="col-span-full p-8 text-center bg-slate-50 rounded-2xl text-xs text-slate-500">
            Belum ada program pendampingan yang dibuat.
        </div>
        @endforelse
    </div>

    <!-- Modal Add Program -->
    <div id="modal-add-program" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900">Buat Program Pendampingan Baru</h3>
                <button type="button" onclick="document.getElementById('modal-add-program').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('admin.programs.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Program *</label>
                    <input type="text" name="name" required placeholder="Contoh: Hilirisasi Cabai & Gula Aren 2026" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi & Tujuan</label>
                    <textarea name="description" rows="3" placeholder="Fokus peningkatan kapasitas petani dan sertifikasi produk..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-program').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1E4D2B] text-white font-bold hover:bg-[#143D24]">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

