@extends('group.layouts.app')

@section('title', 'Belajar & Mentoring — RUMA SiYota')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold tracking-wider uppercase text-[#B88E3E]">PENDAMPINGAN DIGITAL</span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500 font-semibold">SiYota.org</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                Pusat Belajar & Mentoring
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Akses kelas interaktif, jadwal agenda lapangan, panduan teknis satu pintu, dan catatan pendampingan kelompok.
            </p>
        </div>

        <!-- Quick Switch Tab Pills -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl overflow-x-auto max-w-full">
            <a href="{{ route('ruma.belajar', ['tab' => 'kelas']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'kelas' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Kelas Interaktif
            </a>
            <a href="{{ route('ruma.belajar', ['tab' => 'jadwal']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'jadwal' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Jadwal Agenda
            </a>
            <a href="{{ route('ruma.belajar', ['tab' => 'materi']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'materi' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Panduan & Materi
            </a>
            <a href="{{ route('ruma.belajar', ['tab' => 'mentoring']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $tab === 'mentoring' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Catatan Mentoring
            </a>
        </div>
    </div>

    <!-- TAB 1: KELAS & WEBINAR INTERAKTIF -->
    @if($tab === 'kelas')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Program Kelas Binaan SiYota</h3>
                    <p class="text-xs text-slate-500">Materi praktis terarah untuk peningkatan nilai tambah usaha kelompok.</p>
                </div>
                <span class="text-xs font-semibold text-[#B88E3E] bg-[#FDFBF7] px-3 py-1 rounded-full border border-[#E6CE93]/60">
                    {{ $classes->count() }} Modul Kelas
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($classes as $c)
                    <div class="ruma-card ruma-card-hover rounded-3xl overflow-hidden flex flex-col justify-between group">
                        <!-- Image cover -->
                        <div class="relative h-44 w-full overflow-hidden bg-slate-100">
                            <img src="{{ $c->cover_image }}" alt="{{ $c->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            
                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur-xs text-slate-900 shadow-xs">
                                    {{ $c->category }}
                                </span>
                            </div>

                            <!-- Schedule chip -->
                            <div class="absolute bottom-3 left-3 right-3 text-white">
                                <div class="text-[11px] font-medium opacity-90 truncate">
                                    {{ $c->schedule_text ?? 'Jadwal Reguler SiYota' }}
                                </div>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-1.5">
                                <h4 class="font-extrabold text-slate-900 text-base leading-snug group-hover:text-[#B88E3E] transition-colors">
                                    {{ $c->title }}
                                </h4>
                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                    {{ $c->description }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 text-xs text-slate-500 truncate">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="truncate">{{ $c->instructor_name }}</span>
                                </div>

                                @if($c->zoom_url)
                                    <a href="{{ $c->zoom_url }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-[#1E4D2B] hover:bg-[#15381F] text-white text-xs font-bold shadow-xs flex items-center gap-1.5 flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        <span>Masuk Zoom</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TAB 2: JADWAL TERPUSAT -->
    @if($tab === 'jadwal')
        <div class="space-y-4">
            <div class="p-4 rounded-3xl bg-[#FDFBF7] border border-[#E6CE93]/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-100 text-[#B88E3E] flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Agenda Terpusat Pendampingan</h3>
                        <p class="text-xs text-slate-500">Semua pelatihan, jadwal Zoom, kunjungan fasilitator, dan batas tugas dikumpulkan di sini.</p>
                    </div>
                </div>
                <div class="text-xs text-slate-600 font-semibold">
                    Waktu Standar Indonesia Barat (WIB)
                </div>
            </div>

            <div class="space-y-3">
                @foreach($schedules as $s)
                    <div class="ruma-card ruma-card-hover rounded-3xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start sm:items-center gap-4">
                            <!-- Date Badge -->
                            <div class="w-14 h-14 rounded-2xl bg-[#FDFBF7] border border-[#E6CE93]/70 flex flex-col items-center justify-center text-center flex-shrink-0 shadow-2xs">
                                <span class="text-[10px] font-bold text-[#B88E3E] uppercase tracking-wider leading-none">
                                    {{ $s->badge_month ?? $s->event_date->format('M') }}
                                </span>
                                <span class="text-xl font-black text-slate-900 leading-tight">
                                    {{ $s->badge_day ?? $s->event_date->format('d') }}
                                </span>
                            </div>

                            <!-- Details -->
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @php
                                        $typeBadge = match($s->event_type) {
                                            'zoom' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Zoom Meeting'],
                                            'kunjungan' => ['bg' => 'bg-emerald-50 text-[#1E4D2B] border-emerald-200', 'label' => 'Kunjungan Lapangan'],
                                            'deadline' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Batas Pengumpulan'],
                                            'evaluasi' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Evaluasi Usaha'],
                                            default => ['bg' => 'bg-amber-50 text-[#B88E3E] border-amber-200', 'label' => 'Pelatihan Bersama'],
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $typeBadge['bg'] }}">
                                        {{ $typeBadge['label'] }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-semibold">
                                        {{ $s->time_info }}
                                    </span>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-base leading-snug">
                                    {{ $s->title }}
                                </h4>
                                <p class="text-xs text-slate-600 max-w-2xl leading-relaxed">
                                    {{ $s->description }}
                                </p>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="flex items-center gap-2 self-end md:self-center flex-shrink-0">
                            @if(str_contains($s->location_or_link, 'zoom'))
                                <a href="{{ $s->location_or_link }}" target="_blank" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition-all flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <span>Tekan untuk Bergabung</span>
                                </a>
                            @else
                                <span class="px-3.5 py-2 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 font-semibold">
                                    {{ $s->location_or_link ?? 'Lokasi Terdaftar' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TAB 3: PANDUAN & MATERI (Satu Halaman Terpadu Sesuai Brief) -->
    @if($tab === 'materi')
        <div class="space-y-4">
            
            <!-- Category Filter Pills (Keuangan, SOP produksi, Quality Control, Branding, Kemasan, Marketing, Administrasi) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
                @php
                    $categories = [
                        'all' => 'Semua Panduan',
                        'keuangan' => 'Keuangan',
                        'sop_produksi' => 'SOP Produksi',
                        'quality_control' => 'Quality Control',
                        'branding' => 'Branding',
                        'kemasan' => 'Kemasan',
                        'marketing' => 'Marketing',
                        'administrasi' => 'Administrasi Kelompok',
                    ];
                @endphp
                @foreach($categories as $catKey => $catLabel)
                    <a href="{{ route('ruma.belajar', ['tab' => 'materi', 'category' => $catKey]) }}" class="px-3.5 py-1.5 rounded-2xl text-xs font-bold whitespace-nowrap border transition-all {{ $materialCategory === $catKey ? 'bg-[#B88E3E] text-white border-[#B88E3E] shadow-2xs' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
                        {{ $catLabel }}
                    </a>
                @endforeach
            </div>

            <!-- Material List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($materials as $mat)
                    <div class="ruma-card ruma-card-hover rounded-3xl p-5 flex flex-col justify-between group">
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>

                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                        {{ str_replace('_', ' ', $mat->category) }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $mat->file_size_text }}</span>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm leading-snug group-hover:text-[#B88E3E] transition-colors truncate">
                                    {{ $mat->title }}
                                </h4>
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                    {{ $mat->summary }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Diunduh {{ $mat->download_count }} kali</span>
                            <button type="button" onclick="alert('Mengunduh dokumen: {{ $mat->title }} (Format PDF resmi SiYota.org)')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#FDFBF7] hover:bg-[#F8F2E4] text-[#B88E3E] font-bold text-xs border border-[#E6CE93]/60 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Buka / Unduh PDF</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12 text-slate-400 text-xs">
                        Tidak ada dokumen pada kategori ini.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- TAB 4: CATATAN MENTORING -->
    @if($tab === 'mentoring')
        <div class="space-y-4">
            <div class="p-4 rounded-3xl bg-emerald-50/60 border border-emerald-200/80 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-[#1E4D2B] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Catatan Mentoring & Evaluasi Fasilitator</h3>
                    <p class="text-xs text-slate-600">Dokumentasi hasil arahan lapangan langsung dari Tim Pendamping SiYota.</p>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($mentoringNotes as $note)
                    <div class="ruma-card rounded-3xl p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#F8F2E4] text-[#B88E3E] border border-[#E6CE93]/60">
                                    {{ $note->date->format('d F Y') }}
                                </span>
                                <h4 class="font-extrabold text-slate-900 text-sm sm:text-base">
                                    {{ $note->topic }}
                                </h4>
                            </div>
                            <span class="text-xs font-semibold text-slate-500">
                                Oleh: <strong class="text-slate-800">{{ $note->facilitator->name ?? 'Kang Rian' }}</strong>
                            </span>
                        </div>

                        <div class="space-y-2 text-xs sm:text-sm text-slate-700 leading-relaxed">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="font-bold text-slate-800 mb-1">Hasil Observasi:</div>
                                <p class="text-slate-600">{{ $note->observations }}</p>
                            </div>

                            @if($note->action_items)
                                <div class="p-3.5 rounded-2xl bg-amber-50/60 border border-amber-200/60">
                                    <div class="font-bold text-amber-900 mb-1">Rekomendasi Tindak Lanjut:</div>
                                    <p class="text-amber-800">{{ $note->action_items }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs">
                        Belum ada catatan mentoring untuk kelompok ini.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

</div>
@endsection

