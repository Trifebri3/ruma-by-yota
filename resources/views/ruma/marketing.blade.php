@extends('group.layouts.app')

@section('title', 'Marketing Hub — RUMA SiYota')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold tracking-wider uppercase text-[#B88E3E]">MARKETING HUB</span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500 font-semibold">Pendampingan Praktik Nyata</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                Tugas Promosi & Review Konten
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelompok mengasah keterampilan pemasaran digital melalui instruksi jelas, referensi visual, dan koreksi langsung dari Fasilitator SiYota.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-2xl bg-amber-50 text-[#B88E3E] text-xs font-bold border border-[#E6CE93]/60 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Alur Review SiYota Aktif</span>
            </span>
        </div>
    </div>

    <!-- MAIN TWO COLUMN LAYOUT: DAFTAR TUGAS (Kiri) & DETAIL / SUBMIT FORM (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Kolom Kiri: Daftar Tugas Fasilitator (4 Kolom) -->
        <div class="lg:col-span-4 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Daftar Penugasan</h3>
                <span class="text-xs text-slate-400">{{ $tasks->count() }} Tugas</span>
            </div>

            <div class="space-y-2.5">
                @foreach($tasks as $t)
                    @php
                        $sub = $submissions[$t->id] ?? null;
                        $status = $sub ? $sub->status : 'belum_dikerjakan';
                        $isSelected = $selectedTask && $selectedTask->id === $t->id;

                        $statusBadge = match($status) {
                            'dikumpulkan' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Menunggu Review'],
                            'perlu_revisi' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Perlu Revisi'],
                            'disetujui' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Disetujui'],
                            'dipublikasikan' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Dipublikasikan'],
                            default => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'label' => 'Belum Dikerjakan'],
                        };
                    @endphp

                    <a href="{{ route('ruma.marketing', ['task_id' => $t->id]) }}" class="block p-4 rounded-3xl border transition-all {{ $isSelected ? 'bg-white border-[#B88E3E] shadow-md shadow-[#B88E3E]/10 ring-2 ring-[#B88E3E]/20' : 'bg-white border-slate-100 hover:border-slate-200 shadow-2xs' }}">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $statusBadge['bg'] }}">
                                {{ $statusBadge['label'] }}
                            </span>
                            <span class="text-[11px] text-slate-400 font-semibold">
                                {{ $t->deadline->format('d M') }}
                            </span>
                        </div>
                        <h4 class="font-extrabold text-slate-900 text-sm leading-snug">
                            {{ $t->title }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                            {{ $t->description }}
                        </p>
                    </a>
                @endforeach
            </div>

            <!-- Panduan Alur Review Box -->
            <div class="p-4 rounded-3xl bg-[#FDFBF7] border border-[#E6CE93]/60 space-y-2 text-xs">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#B88E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Tahapan Pengumpulan Konten:</span>
                </div>
                <ol class="space-y-1 text-slate-600 pl-4 list-decimal leading-relaxed">
                    <li>Pahami instruksi & unduh referensi.</li>
                    <li>Unggah foto karya dan teks caption.</li>
                    <li>Fasilitator memeriksa dan memberi masukan.</li>
                    <li>Perbaiki jika ada revisi, lalu publikasikan mandiri!</li>
                </ol>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Tugas & Form Submit (8 Kolom) -->
        <div class="lg:col-span-8 space-y-6">
            @if($selectedTask)
                <div class="ruma-card rounded-3xl p-6 sm:p-7 space-y-6">
                    
                    <!-- Header Tugas -->
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-4 border-b border-slate-100">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-[#B88E3E]">
                                    Tugas Kelompok
                                </span>
                                <span class="text-xs text-slate-500 font-semibold">
                                    Deadline: {{ $selectedTask->deadline->format('d F Y, H:i') }} WIB
                                </span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                                {{ $selectedTask->title }}
                            </h2>
                            <p class="text-xs text-slate-500 font-medium">
                                Fasilitator Pembimbing: <strong class="text-slate-800">{{ $selectedTask->facilitator->name ?? 'Kang Rian (SiYota)' }}</strong>
                            </p>
                        </div>

                        <!-- Status Badge -->
                        @php
                            $curStatus = $currentSubmission?->status ?? 'belum_dikerjakan';
                        @endphp
                        <div>
                            @if($curStatus === 'belum_dikerjakan')
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                    Belum Dikerjakan
                                </span>
                            @elseif($curStatus === 'dikumpulkan')
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                                    Menunggu Review Fasilitator
                                </span>
                            @elseif($curStatus === 'perlu_revisi')
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300 animate-pulse">
                                    Perlu Revisi
                                </span>
                            @elseif($curStatus === 'disetujui')
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    Disetujui Fasilitator ✓
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-300">
                                    Dipublikasikan
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Instruksi Yang Harus Dilakukan -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#B88E3E]"></span>
                            <span>Yang Harus Dilakukan:</span>
                        </h4>

                        <div class="p-4 rounded-2xl bg-[#FDFBF7] border border-[#E6CE93]/50 space-y-2">
                            @if(is_array($selectedTask->instructions))
                                <ul class="space-y-1.5 text-xs sm:text-sm text-slate-700">
                                    @foreach($selectedTask->instructions as $idx => $inst)
                                        <li class="flex items-start gap-2">
                                            <span class="w-5 h-5 rounded-full bg-amber-200/60 text-[#B88E3E] font-bold text-[11px] flex items-center justify-center flex-shrink-0 mt-0.5">
                                                {{ $idx + 1 }}
                                            </span>
                                            <span>{{ $inst }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-xs text-slate-600">{{ $selectedTask->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Referensi Dari Fasilitator (Sesuai Brief: Panduan Foto Produk.pdf & Referensi Visual) -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#B88E3E]"></span>
                            <span>Referensi Dari Fasilitator:</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Dokumen PDF -->
                            <div class="p-3.5 rounded-2xl bg-white border border-slate-200 flex items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-xs truncate">{{ $selectedTask->reference_pdf_name ?? 'Panduan Foto Produk.pdf' }}</div>
                                        <div class="text-[10px] text-slate-400">Dokumen panduan resmi</div>
                                    </div>
                                </div>
                                <button type="button" onclick="alert('Membuka dokumen referensi: {{ $selectedTask->reference_pdf_name }}')" class="text-xs font-bold text-[#B88E3E] hover:underline flex-shrink-0">
                                    Buka PDF
                                </button>
                            </div>

                            <!-- Contoh Referensi Visual -->
                            @if($selectedTask->reference_image_url)
                                <div class="p-3.5 rounded-2xl bg-white border border-slate-200 flex items-center justify-between gap-3 shadow-2xs">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img src="{{ $selectedTask->reference_image_url }}" alt="Contoh" class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 text-xs truncate">Contoh Foto Standar</div>
                                            <div class="text-[10px] text-slate-400">Referensi visual SiYota</div>
                                        </div>
                                    </div>
                                    <a href="{{ $selectedTask->reference_image_url }}" target="_blank" class="text-xs font-bold text-[#B88E3E] hover:underline flex-shrink-0">
                                        Lihat Foto
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- KOTAK FEEDBACK & KOMENTAR DARI FASILITATOR (JIKA SUDAH DIREVIEW / PERLU REVISI) -->
                    @if($currentSubmission && $currentSubmission->facilitator_feedback)
                        <div class="p-4 sm:p-5 rounded-2xl {{ $currentSubmission->status === 'perlu_revisi' ? 'bg-rose-50/80 border border-rose-200' : 'bg-emerald-50/80 border border-emerald-200' }} space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider {{ $currentSubmission->status === 'perlu_revisi' ? 'text-rose-800' : 'text-emerald-800' }} flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    <span>Catatan Feedback Fasilitator ({{ $currentSubmission->reviewed_at ? $currentSubmission->reviewed_at->format('d M Y') : 'Terbaru' }}):</span>
                                </span>
                                <span class="text-[11px] font-semibold text-slate-600">
                                    Oleh: Kang Rian
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-800 font-medium italic leading-relaxed pl-6">
                                "{{ $currentSubmission->facilitator_feedback }}"
                            </p>
                        </div>
                    @endif

                    <!-- FORM SUBMIT / UNGGAH HASIL KONTEN KARYA KELOMPOK -->
                    <div class="pt-4 border-t border-slate-100 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-extrabold text-slate-900">
                                {{ $currentSubmission ? 'Karya yang Telah Dikumpulkan' : 'Form Pengumpulan Hasil Konten' }}
                            </h4>
                            @if($currentSubmission && $currentSubmission->submitted_at)
                                <span class="text-[11px] text-slate-400">
                                    Dikirim: {{ $currentSubmission->submitted_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>

                        <!-- Preview Foto yang Sudah Ada -->
                        @if($currentSubmission && $currentSubmission->photo_url)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center gap-4">
                                <img src="{{ $currentSubmission->photo_url }}" alt="Karya Kelompok" class="w-full sm:w-40 h-32 rounded-xl object-cover shadow-2xs">
                                <div class="space-y-1.5 flex-1 min-w-0">
                                    <div class="text-xs font-bold text-slate-700">Caption Promosi yang Dibuat:</div>
                                    <p class="text-xs text-slate-600 leading-relaxed bg-white p-3 rounded-xl border border-slate-200">
                                        {{ $currentSubmission->caption }}
                                    </p>
                                    @if($currentSubmission->notes)
                                        <p class="text-[11px] text-slate-500 italic">
                                            Catatan untuk mentor: "{{ $currentSubmission->notes }}"
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Form Upload / Revisi -->
                        @if(!$currentSubmission || in_array($currentSubmission->status, ['belum_dikerjakan', 'perlu_revisi']))
                            <form action="{{ route('ruma.marketing.submit') }}" method="POST" class="space-y-4 p-5 rounded-2xl bg-[#FDFBF7] border border-[#E6CE93]/60">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $selectedTask->id }}">
                                <input type="hidden" name="group_id" value="{{ $group->id }}">

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                        Foto Karya Produk (URL Foto / Galeri HP)
                                    </label>
                                    <input type="text" name="photo_url" value="{{ $currentSubmission?->photo_url ?? 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=800&q=80' }}" placeholder="https://..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-white focus:outline-none focus:border-[#B88E3E]">
                                    <p class="text-[11px] text-slate-400 mt-1">Pastikan foto diambil di tempat terang dan fokus pada produk olahan.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                        Teks Caption Promosi (WhatsApp / Media Sosial)
                                    </label>
                                    <textarea name="caption" rows="3" required placeholder="Tuliskan cerita produk, keunggulan rasa/kualitas, berat bersih, dan ajakan pesan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm bg-white focus:outline-none focus:border-[#B88E3E]">{{ $currentSubmission?->caption ?? 'Sambal Rawit Merah Uleg KWT Sumber Rejeki. Dipetik langsung dari kebun Pasir Gebang Tasikmalaya, tanpa bahan pengawet. Netto: 180gr. Hubungi: 0812-3456-7890.' }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                        Catatan / Pertanyaan untuk Fasilitator (Opsional)
                                    </label>
                                    <input type="text" name="notes" value="{{ $currentSubmission?->notes ?? 'Foto diambil saat panen petik pagi.' }}" placeholder="Contoh: Kami bingung menentukan latar belakang yang pas..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm bg-white focus:outline-none focus:border-[#B88E3E]">
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <button type="submit" class="px-6 py-3 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#B88E3E]/20 transition-all flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span>{{ $currentSubmission && $currentSubmission->status === 'perlu_revisi' ? 'Kirim Ulang Hasil Perbaikan' : 'Kumpulkan Hasil Konten' }}</span>
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-xs text-emerald-800 flex items-center justify-between">
                                <span>Konten ini sudah disetujui oleh Fasilitator SiYota. Kelompok siap mempublikasikan ke status WhatsApp dan media sosial secara mandiri!</span>
                                <span class="font-extrabold text-emerald-700">✓ Terverifikasi</span>
                            </div>
                        @endif

                    </div>

                </div>
            @endif
        </div>

    </div>

</div>
@endsection

