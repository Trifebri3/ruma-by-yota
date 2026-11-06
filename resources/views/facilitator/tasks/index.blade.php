@extends('facilitator.layouts.app')

@section('title', 'Kelola Tugas Pemasaran — Fasilitator SiYota')

@section('content')
<div class="space-y-6 pb-16">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#1E4D2B]">Penugasan Kelompok</span>
            <h1 class="text-2xl font-black text-slate-900">Tugas Pemasaran & Konten</h1>
        </div>
        <button onclick="document.getElementById('modal-add-task').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-xs flex items-center gap-1.5 shadow-xs">
            + Buat Tugas Baru
        </button>
    </div>

    <!-- Tasks List -->
    <div class="space-y-4">
        @forelse($tasks as $t)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold {{ $t->priority === 'tinggi' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">
                        {{ strtoupper($t->priority) }}
                    </span>
                    <span class="text-xs text-slate-400">Batas Waktu: {{ $t->deadline->format('d M Y, H:i') }} WIB</span>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">{{ $t->title }}</h3>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $t->description }}</p>

                @if($t->reference_pdf_name)
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-700">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span>{{ $t->reference_pdf_name }}</span>
                </div>
                @endif
            </div>

            <div class="text-right shrink-0">
                <a href="{{ route('facilitator.reviews.index') }}" class="px-4 py-2 rounded-xl bg-[#1E4D2B] hover:bg-[#143D24] text-white font-bold text-xs transition-colors">
                    Lihat Kiriman Kelompok
                </a>
            </div>
        </div>
        @empty
        <div class="p-8 text-center bg-slate-50 rounded-2xl text-xs text-slate-500">
            Belum ada tugas yang dibuat.
        </div>
        @endforelse
    </div>

    <!-- Modal Add Task -->
    <div id="modal-add-task" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl p-6 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900">Buat Tugas Baru untuk Kelompok</h3>
                <button type="button" onclick="document.getElementById('modal-add-task').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('facilitator.tasks.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Tugas *</label>
                    <input type="text" name="title" required placeholder="Contoh: Ambil 3 Foto Produk Cabai & Buat Caption" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tenggat Waktu (Deadline) *</label>
                        <input type="datetime-local" name="deadline" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Prioritas *</label>
                        <select name="priority" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                            <option value="normal">Normal</option>
                            <option value="tinggi">Tinggi (Mendesak)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Tugas *</label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan tujuan dan hasil yang diharapkan..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Foto Contoh / Referensi</label>
                    <input type="url" name="reference_image_url" placeholder="https://..." class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Dokumen Panduan Standar</label>
                    <input type="text" name="reference_pdf_name" value="Panduan Foto & Branding SiYota.pdf" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-task').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#B88E3E] text-white font-bold hover:bg-[#9E752D]">Terbitkan Tugas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

