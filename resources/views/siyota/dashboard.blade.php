@extends('facilitator.layouts.app')

@section('title', 'Dashboard Fasilitator SiYota — Monitoring & Evaluasi')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold tracking-wider uppercase text-[#1E4D2B]">SIYOTA • FACILITATOR & ADMIN</span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#F1F7F3] text-[#1E4D2B] border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Mode Pendamping Lapangan
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Monitoring Kelompok Binaan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Pusat pendampingan, review tugas marketing, evaluasi QC produk, dan verifikasi kelompok baru.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="document.getElementById('modal-catat-mentoring').classList.remove('hidden')" class="px-4 py-2.5 rounded-2xl bg-[#1E4D2B] hover:bg-[#15381F] text-white font-bold text-xs shadow-xs transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>+ Catat Mentoring</span>
            </button>
            <a href="{{ route('ruma.dashboard') }}" class="px-3.5 py-2.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-all">
                Buka Tampilan Kelompok
            </a>
        </div>
    </div>

    <!-- 4 KARTU STATISTIK MONITORING (Sesuai Ilustrasi Brief: Kelompok aktif 24, Tugas menunggu review 12, Mentoring bulan ini 8, Perlu perhatian 3) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="ruma-card rounded-3xl p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kelompok Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-[#1E4D2B] flex items-center justify-center font-bold">
                    ✓
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-black text-slate-900 tracking-tight">24</div>
                <div class="text-xs text-slate-500 mt-0.5">Kelompok dampingan</div>
            </div>
        </div>

        <div class="ruma-card rounded-3xl p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tugas Menunggu</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                    ⏳
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-black text-blue-700 tracking-tight">12</div>
                <div class="text-xs text-slate-500 mt-0.5">Konten siap dinilai</div>
            </div>
        </div>

        <div class="ruma-card rounded-3xl p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mentoring Bulan Ini</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#B88E3E] flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-black text-slate-900 tracking-tight">8</div>
                <div class="text-xs text-slate-500 mt-0.5">Sesi & kunjungan</div>
            </div>
        </div>

        <div class="ruma-card rounded-3xl p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Perlu Perhatian</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-black text-rose-600 tracking-tight">3</div>
                <div class="text-xs text-rose-600 font-medium mt-0.5">Revisi QC & konten</div>
            </div>
        </div>

    </div>

    <!-- Navigation Tabs Fasilitator -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
        <a href="{{ route('facilitator.dashboard', ['tab' => 'kelompok']) }}" class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'kelompok' ? 'bg-[#1E4D2B] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            Kelompok Binaan ({{ $groups->count() }})
        </a>
        <a href="{{ route('facilitator.dashboard', ['tab' => 'submissions']) }}" class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'submissions' ? 'bg-[#1E4D2B] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            Review Tugas Konten ({{ $submissions->count() }})
        </a>
        <a href="{{ route('facilitator.dashboard', ['tab' => 'qc']) }}" class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'qc' ? 'bg-[#1E4D2B] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            Evaluasi Mutu Produk ({{ $products->count() }})
        </a>
        <a href="{{ route('facilitator.dashboard', ['tab' => 'verifikasi']) }}" class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'verifikasi' ? 'bg-[#1E4D2B] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            Verifikasi Pendaftaran ({{ $pendingGroups->count() }})
        </a>
    </div>

    <!-- ==================== TAB 1: KELOMPOK BINAAN ==================== -->
    @if($tab === 'kelompok')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-900">Daftar Kelompok Binaan Aktif</h3>
                <span class="text-xs text-slate-400">Priangan Timur & Sekitarnya</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($groups as $g)
                    <div class="ruma-card ruma-card-hover rounded-3xl p-5 flex flex-col justify-between space-y-4 group">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $g->status === 'active' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                    {{ $g->status === 'active' ? 'Aktif' : 'Menunggu Verifikasi' }}
                                </span>
                                <span class="text-xs font-bold text-slate-400">{{ $g->regency }}</span>
                            </div>

                            <h4 class="font-extrabold text-slate-900 text-lg group-hover:text-[#B88E3E] transition-colors">
                                {{ $g->name }}
                            </h4>

                            <div class="text-xs text-slate-600 space-y-1">
                                <div><strong>Komoditas:</strong> {{ $g->commodity_summary ?? '-' }}</div>
                                <div><strong>Ketua:</strong> {{ $g->leader_name }}</div>
                                <div><strong>Kontak:</strong> {{ $g->contact_phone }}</div>
                            </div>

                            <div class="pt-2 flex items-center gap-3 text-xs text-slate-500">
                                <span>{{ $g->commodities->count() }} Komoditas</span>
                                <span>•</span>
                                <span>{{ $g->products->count() }} Produk</span>
                                <span>•</span>
                                <span>{{ $g->members->count() }} Anggota</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('facilitator.group.detail', $g->id) }}" class="text-xs font-bold text-[#1E4D2B] hover:underline flex items-center gap-1">
                                <span>Lihat Profil Lengkap</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ route('switch.persona', 'ibu_siti') }}" class="px-3 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                                Masuk Dasbor
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ==================== TAB 2: REVIEW TUGAS KONTEN MARKETING ==================== -->
    @if($tab === 'submissions')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Review Pengumpulan Konten Marketing Kelompok</h3>
                    <p class="text-xs text-slate-500">Beri feedback langsung agar kelompok belajar praktik foto dan penulisan promosi produk.</p>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($submissions as $sub)
                    <div class="ruma-card rounded-3xl p-6 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-slate-900 text-base">{{ $sub->group->name }}</span>
                                    <span class="text-xs text-slate-400">• {{ $sub->group->regency }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-[#B88E3E] mt-0.5">
                                    Tugas: {{ $sub->task->title }}
                                </h4>
                            </div>

                            @php
                                $statusBadge = match($sub->status) {
                                    'dikumpulkan' => ['bg' => 'bg-blue-100 text-blue-800', 'label' => 'Menunggu Review Fasilitator'],
                                    'perlu_revisi' => ['bg' => 'bg-rose-100 text-rose-800', 'label' => 'Perlu Revisi'],
                                    'disetujui' => ['bg' => 'bg-emerald-100 text-emerald-800', 'label' => 'Disetujui ✓'],
                                    default => ['bg' => 'bg-slate-100 text-slate-700', 'label' => ucfirst($sub->status)],
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusBadge['bg'] }} self-start">
                                {{ $statusBadge['label'] }}
                            </span>
                        </div>

                        <!-- Content Submitted -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-center">
                            @if($sub->photo_url)
                                <div class="md:col-span-4">
                                    <img src="{{ $sub->photo_url }}" alt="Karya" class="w-full h-48 rounded-2xl object-cover shadow-2xs">
                                </div>
                            @endif

                            <div class="md:col-span-8 space-y-3">
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                    <div class="text-[11px] font-bold text-slate-400 uppercase">Caption Promosi dari Kelompok:</div>
                                    <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">
                                        {{ $sub->caption }}
                                    </p>
                                    @if($sub->notes)
                                        <div class="text-xs text-slate-500 italic pt-1">
                                            Catatan: "{{ $sub->notes }}"
                                        </div>
                                    @endif
                                </div>

                                @if($sub->facilitator_feedback)
                                    <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200 text-xs text-amber-900">
                                        <strong>Feedback Sebelumnya:</strong> "{{ $sub->facilitator_feedback }}"
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Review Action Form -->
                        <form action="{{ route('facilitator.submission.review', $sub->id) }}" method="POST" class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                            @csrf
                            <input type="text" name="facilitator_feedback" required placeholder="Beri feedback: e.g. Foto sudah segar! Tambahkan info berat bersih dan nomor pemesanan..." class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#1E4D2B] bg-white">
                            
                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <button type="submit" name="status" value="perlu_revisi" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200">
                                    Minta Revisi
                                </button>
                                <button type="submit" name="status" value="disetujui" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-[#1E4D2B] hover:bg-[#15381F] text-white text-xs font-bold shadow-xs">
                                    Setujui Konten ✓
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ==================== TAB 3: EVALUASI MUTU & QC PRODUK ==================== -->
    @if($tab === 'qc')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Evaluasi Standar Kualitas Produk (QC 4 Pilar)</h3>
                    <p class="text-xs text-slate-500">Pandu kelompok mencapai standar kemasan aman, legalitas jelas, dan tampilan menarik.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($products as $prod)
                    <div class="ruma-card rounded-3xl p-6 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="text-xs font-semibold text-slate-400">{{ $prod->group->name }}</span>
                                <h4 class="font-extrabold text-slate-900 text-base leading-snug">{{ $prod->name }}</h4>
                                <div class="text-xs text-slate-500">Harga: Rp{{ number_format($prod->price, 0, ',', '.') }} / {{ $prod->unit }}</div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $prod->status === 'sesuai_panduan' ? 'bg-emerald-100 text-emerald-800' : ($prod->status === 'perlu_diperbaiki' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ str_replace('_', ' ', $prod->status) }}
                            </span>
                        </div>

                        <!-- Update Form for QC -->
                        <form action="{{ route('facilitator.product.evaluate', $prod->id) }}" method="POST" class="space-y-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                            @csrf
                            <div class="font-bold text-slate-700">Checklist 4 Pilar QC:</div>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] text-slate-500 mb-0.5">Label Produk</label>
                                    <select name="qc_label_status" class="w-full p-2 rounded-lg border border-slate-200 bg-white">
                                        <option value="sesuai" {{ $prod->qc_label_status === 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                                        <option value="perbaikan" {{ $prod->qc_label_status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                        <option value="belum_dinilai" {{ $prod->qc_label_status === 'belum_dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] text-slate-500 mb-0.5">Kemasan</label>
                                    <select name="qc_packaging_status" class="w-full p-2 rounded-lg border border-slate-200 bg-white">
                                        <option value="sesuai" {{ $prod->qc_packaging_status === 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                                        <option value="perbaikan" {{ $prod->qc_packaging_status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                        <option value="belum_dinilai" {{ $prod->qc_packaging_status === 'belum_dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] text-slate-500 mb-0.5">Info Produk (P-IRT, Exp)</label>
                                    <select name="qc_info_status" class="w-full p-2 rounded-lg border border-slate-200 bg-white">
                                        <option value="sesuai" {{ $prod->qc_info_status === 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                                        <option value="perbaikan" {{ $prod->qc_info_status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                        <option value="belum_dinilai" {{ $prod->qc_info_status === 'belum_dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] text-slate-500 mb-0.5">Foto Produk</label>
                                    <select name="qc_photo_status" class="w-full p-2 rounded-lg border border-slate-200 bg-white">
                                        <option value="sesuai" {{ $prod->qc_photo_status === 'sesuai' ? 'selected' : '' }}>Sesuai</option>
                                        <option value="perbaikan" {{ $prod->qc_photo_status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                        <option value="belum_dinilai" {{ $prod->qc_photo_status === 'belum_dinilai' ? 'selected' : '' }}>Belum Dinilai</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] text-slate-500 mb-0.5">Status Keseluruhan</label>
                                <select name="status" class="w-full p-2 rounded-lg border border-slate-200 bg-white font-bold">
                                    <option value="sesuai_panduan" {{ $prod->status === 'sesuai_panduan' ? 'selected' : '' }}>Sesuai Panduan SiYota</option>
                                    <option value="perlu_diperbaiki" {{ $prod->status === 'perlu_diperbaiki' ? 'selected' : '' }}>Perlu Diperbaiki</option>
                                    <option value="dalam_evaluasi" {{ $prod->status === 'dalam_evaluasi' ? 'selected' : '' }}>Dalam Evaluasi</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] text-slate-500 mb-0.5">Catatan Bimbingan / Saran Kemasan</label>
                                <textarea name="facilitator_notes" rows="2" placeholder="Saran fasilitator untuk kelompok..." class="w-full p-2 rounded-lg border border-slate-200 bg-white text-xs">{{ $prod->facilitator_notes }}</textarea>
                            </div>

                            <div class="flex justify-end pt-1">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-[#1E4D2B] text-white font-bold text-xs">
                                    Perbarui Evaluasi QC
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ==================== TAB 4: VERIFIKASI PENDAFTARAN KELOMPOK BARU ==================== -->
    @if($tab === 'verifikasi')
        <div class="space-y-4">
            <div class="p-4 rounded-3xl bg-amber-50/70 border border-[#E6CE93]/60 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Antrean Verifikasi Kelompok Baru</h3>
                    <p class="text-xs text-slate-600">Kelompok tidak langsung aktif setelah mengisi form web. Admin SiYota menetapkan pengurus dan aktivasi akun.</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-amber-200/80 text-amber-900">
                    {{ $pendingGroups->count() }} Menunggu
                </span>
            </div>

            <div class="space-y-4">
                @forelse($pendingGroups as $pg)
                    <div class="ruma-card rounded-3xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-900">
                                    Status: Menunggu Verifikasi
                                </span>
                                <h4 class="font-extrabold text-slate-900 text-lg mt-1">{{ $pg->name }}</h4>
                                <div class="text-xs text-slate-500">{{ $pg->village }}, {{ $pg->district }}, {{ $pg->regency }}</div>
                            </div>
                            <div class="text-right text-xs text-slate-400">
                                Terdaftar: {{ $pg->created_at->format('d M Y') }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 text-xs">
                            <div>
                                <span class="text-slate-400 block">Ketua Kelompok:</span>
                                <strong class="text-slate-800 text-sm">{{ $pg->leader_name }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Kontak WhatsApp:</span>
                                <strong class="text-slate-800 text-sm">{{ $pg->contact_phone }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Potensi Komoditas:</span>
                                <strong class="text-[#B88E3E] text-sm">{{ $pg->commodity_summary }}</strong>
                            </div>
                        </div>

                        <!-- Verification Action Form -->
                        <form action="{{ route('facilitator.group.verify', $pg->id) }}" method="POST" class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                            @csrf
                            <input type="text" name="notes" placeholder="Catatan verifikasi atau alasan..." value="Data kelompok telah diverifikasi, program pendampingan siap dimulai." class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-white">
                            
                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <button type="submit" name="action" value="rejected" class="px-4 py-2.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold border border-rose-200">
                                    Tolak
                                </button>
                                <button type="submit" name="action" value="active" class="px-6 py-2.5 rounded-xl bg-[#1E4D2B] hover:bg-[#15381F] text-white text-xs font-bold shadow-xs">
                                    Setujui & Aktifkan Akun Kelompok ✓
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs">
                        Tidak ada pendaftaran kelompok yang menunggu verifikasi saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- MODAL CATAT MENTORING -->
    <div id="modal-catat-mentoring" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#1E4D2B] flex items-center justify-center font-bold">
                        ✍️
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-base">Catat Kunjungan / Mentoring Baru</h3>
                </div>
                <button type="button" onclick="document.getElementById('modal-catat-mentoring').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('facilitator.mentoring.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Kelompok Binaan</label>
                    <select name="group_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                        @foreach($groups as $grp)
                            <option value="{{ $grp->id }}">{{ $grp->name }} ({{ $grp->regency }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Kunjungan</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Topik Utama</label>
                        <input type="text" name="topic" required placeholder="Contoh: Evaluasi Kemasan & Kas" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Hasil Observasi Lapangan</label>
                    <textarea name="observations" rows="3" required placeholder="Temuan kondisi lapangan, kekompakan kelompok, kualitas produk..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rekomendasi Tindak Lanjut</label>
                    <textarea name="action_items" rows="2" placeholder="Hal penting yang harus diselesaikan kelompok sebelum kunjungan berikutnya..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-catat-mentoring').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#1E4D2B] hover:bg-[#15381F] text-white font-bold text-xs shadow-xs">
                        Simpan Catatan Mentoring
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

