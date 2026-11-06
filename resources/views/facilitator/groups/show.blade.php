@extends('facilitator.layouts.app')

@section('title', 'Detail Monitoring ' . $group->name . ' — Fasilitator SiYota')

@section('content')
<div class="space-y-8 pb-16">

    <!-- Top Breadcrumb & Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('facilitator.groups.index') }}" class="hover:text-[#B88E3E]">Kelompok Binaan</a>
                <span>/</span>
                <span class="text-slate-800">{{ $group->name }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">{{ $group->name }}</h1>
            <p class="text-xs text-slate-500">{{ $group->regency }} • PIC: {{ $group->leader_name }} ({{ $group->contact_phone }})</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $group->contact_phone) }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-xs">
                Hubungi PIC via WhatsApp
            </a>
            <button onclick="document.getElementById('modal-add-mentoring').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#1E4D2B] hover:bg-[#143D24] text-white font-bold text-xs flex items-center gap-1.5 shadow-xs">
                + Catat Mentoring Lapangan
            </button>
        </div>
    </div>

    <!-- 4 Stats Overview Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs text-slate-400 font-bold block uppercase">Lahan Terdata</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $group->lands->count() }} Hamparan</span>
            <span class="text-xs text-emerald-700 font-medium">{{ number_format($group->lands->sum('area_m2'), 0, ',', '.') }} m²</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs text-slate-400 font-bold block uppercase">Komoditas Aktif</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $group->commodities->count() }} Jenis</span>
            <span class="text-xs text-slate-500">{{ $group->commodity_summary ?? '-' }}</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs text-slate-400 font-bold block uppercase">Produk Olahan</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $group->products->count() }} Produk</span>
            <span class="text-xs text-amber-600 font-medium">{{ $group->products->where('status', 'sesuai_panduan')->count() }} Sesuai QC</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs text-slate-400 font-bold block uppercase">Saldo Kas Tercatat</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">Rp {{ number_format($group->total_balance, 0, ',', '.') }}</span>
            <span class="text-xs text-slate-500">{{ $group->financialRecords->count() }} Transaksi</span>
        </div>
    </div>

    <!-- Product Quality Control (QC) Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#B88E3E]">Pusat Evaluasi Mutu</span>
                <h2 class="text-lg font-black text-slate-900">Evaluasi Quality Control (QC) Produk</h2>
            </div>
            <span class="text-xs text-slate-500">Evaluasi 4 pilar standar SiYota</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($group->products as $product)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs space-y-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $product->photo_url ?? 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=200&q=80' }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">{{ $product->name }}</h3>
                            <p class="text-xs font-bold text-[#B88E3E]">Rp {{ number_format($product->price, 0, ',', '.') }} / {{ $product->unit }}</p>
                            <p class="text-[11px] text-slate-500">{{ $product->packaging_type }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $product->status === 'sesuai_panduan' ? 'bg-emerald-100 text-emerald-800' : ($product->status === 'perlu_diperbaiki' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ str_replace('_', ' ', ucfirst($product->status)) }}
                    </span>
                </div>

                <!-- Form Evaluasi QC Fasilitator -->
                <form action="{{ route('facilitator.products.evaluate', $product->id) }}" method="POST" class="bg-slate-50 p-3.5 rounded-xl space-y-3 text-xs border border-slate-100">
                    @csrf
                    <span class="block font-extrabold text-slate-700">Form Evaluasi 4 Pilar QC:</span>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-medium text-slate-600 mb-0.5">1. Label Produk:</label>
                            <select name="qc_label_status" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white">
                                <option value="sesuai" {{ $product->qc_label_status === 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                                <option value="perbaikan" {{ $product->qc_label_status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="belum_dinilai" {{ $product->qc_label_status === 'belum_dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-600 mb-0.5">2. Kemasan:</label>
                            <select name="qc_packaging_status" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white">
                                <option value="sesuai" {{ $product->qc_packaging_status === 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                                <option value="perbaikan" {{ $product->qc_packaging_status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="belum_dinilai" {{ $product->qc_packaging_status === 'belum_dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-600 mb-0.5">3. Informasi & Legalitas:</label>
                            <select name="qc_info_status" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white">
                                <option value="sesuai" {{ $product->qc_info_status === 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                                <option value="perbaikan" {{ $product->qc_info_status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="belum_dinilai" {{ $product->qc_info_status === 'belum_dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-600 mb-0.5">4. Foto Produk:</label>
                            <select name="qc_photo_status" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white">
                                <option value="sesuai" {{ $product->qc_photo_status === 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                                <option value="perbaikan" {{ $product->qc_photo_status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="belum_dinilai" {{ $product->qc_photo_status === 'belum_dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <label class="block font-medium text-slate-600 mb-0.5">Status Produk Akhir:</label>
                            <select name="status" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white font-bold">
                                <option value="sesuai_panduan" {{ $product->status === 'sesuai_panduan' ? 'selected' : '' }}>Sesuai Panduan</option>
                                <option value="perlu_diperbaiki" {{ $product->status === 'perlu_diperbaiki' ? 'selected' : '' }}>Perlu Diperbaiki</option>
                                <option value="dalam_evaluasi" {{ $product->status === 'dalam_evaluasi' ? 'selected' : '' }}>Dalam Evaluasi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-600 mb-0.5">Catatan Perbaikan:</label>
                            <input type="text" name="facilitator_notes" value="{{ $product->facilitator_notes }}" placeholder="Saran font label, nomor P-IRT..." class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white">
                        </div>
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-[#1E4D2B] text-white font-bold text-xs hover:bg-[#143D24]">
                            Simpan Evaluasi QC
                        </button>
                    </div>
                </form>
            </div>
            @empty
            <div class="col-span-full p-8 text-center bg-slate-50 rounded-2xl text-xs text-slate-500">
                Kelompok ini belum mendaftarkan produk olahan.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Mentoring & Field Visits History -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-extrabold text-slate-900">Riwayat Mentoring & Kunjungan Lapangan</h2>
            <button onclick="document.getElementById('modal-add-mentoring').classList.remove('hidden')" class="text-xs font-bold text-[#1E4D2B] hover:underline">
                + Tambah Kunjungan
            </button>
        </div>

        <div class="space-y-3">
            @forelse($group->mentoringNotes as $note)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-900 text-sm">{{ $note->topic }}</span>
                    <span class="text-slate-400">{{ \Carbon\Carbon::parse($note->date)->format('d F Y') }}</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $note->observations }}</p>
                @if($note->action_items)
                <div class="bg-emerald-50/70 p-2.5 rounded-xl border border-emerald-200/50 text-xs text-emerald-900">
                    <b class="text-emerald-800">Tindak Lanjut:</b> {{ $note->action_items }}
                </div>
                @endif
            </div>
            @empty
            <div class="p-6 text-center bg-slate-50 rounded-2xl text-xs text-slate-500">
                Belum ada catatan mentoring lapangan untuk kelompok ini.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Status Kepatuhan SOP & Dokumen Panduan Kelompok (Dua Wadah Terpisah) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#B88E3E]">Audit Belajar Kelompok</span>
                <h2 class="text-base font-extrabold text-slate-900">Kepatuhan Akses SOP & Dokumen Panduan</h2>
            </div>
            <span class="text-xs text-slate-500">Monitoring riwayat pembacaan dokumen teknis</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- WADAH 1: SUDAH DIBUKA & DIBACA KELOMPOK -->
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h3 class="text-xs font-black uppercase tracking-wider text-emerald-800">Wadah: Sudah Dibuka Kelompok</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                        {{ $openedMaterials->count() }} Dokumen
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($openedMaterials as $doc)
                    <div class="bg-white rounded-2xl border border-emerald-200/80 p-3.5 shadow-xs flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-extrabold text-slate-900 truncate">{{ $doc->title }}</h4>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1 text-[11px] text-slate-500">
                                <span class="text-emerald-700 font-bold">Terakhir dibuka: {{ $doc->read_log->opened_at ? $doc->read_log->opened_at->format('d M Y, H:i') : '-' }} WIB</span>
                                <span class="text-slate-300">•</span>
                                <span>Dibuka {{ $doc->read_log->open_count }}x</span>
                                @if($doc->read_log->reader_name)
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-700 font-semibold">{{ $doc->read_log->reader_name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400">
                        Kelompok belum pernah membuka satupun SOP / materi resmi.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- WADAH 2: BELUM PERNAH DIBUKA OLEH KELOMPOK -->
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h3 class="text-xs font-black uppercase tracking-wider text-amber-800">Wadah: Belum Pernah Dibuka</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">
                        {{ $unopenedMaterials->count() }} Perlu Perhatian
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($unopenedMaterials as $doc)
                    <div class="bg-white rounded-2xl border border-amber-200/80 p-3.5 shadow-xs flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-extrabold text-slate-900 truncate">{{ $doc->title }}</h4>
                            <p class="text-[11px] text-amber-700 font-medium mt-0.5">Belum dibuka oleh pengurus kelompok. Ingatkan pada kunjungan berikutnya.</p>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center bg-emerald-50 rounded-2xl border border-emerald-200 text-xs text-emerald-800 font-bold">
                        Luar biasa! Semua SOP dan dokumen panduan telah dibuka dan dibaca oleh kelompok.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Mentoring Note -->
    <div id="modal-add-mentoring" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900">Catat Mentoring Lapangan</h3>
                <button type="button" onclick="document.getElementById('modal-add-mentoring').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('facilitator.mentoring.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <input type="hidden" name="group_id" value="{{ $group->id }}">

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Topik Kunjungan *</label>
                        <input type="text" name="topic" required placeholder="Contoh: Evaluasi Kemasan & Lahan" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Kunjungan *</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Observasi & Catatan Lapangan *</label>
                    <textarea name="observations" rows="3" required placeholder="Kondisi pengurus, kendala panen, standar sanitasi kemasan..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Arahan Tindak Lanjut (Action Items)</label>
                    <textarea name="action_items" rows="2" placeholder="Hal yang harus diperbaiki oleh kelompok sebelum kunjungan berikutnya..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-mentoring').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#1E4D2B] text-white font-bold hover:bg-[#143D24]">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

