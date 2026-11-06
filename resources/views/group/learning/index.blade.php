@extends('group.layouts.app')

@section('title', 'Modul Belajar & SOP Kelompok — RUMA')

@section('content')
<div class="space-y-4">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#1E4D2B]">Pusat Belajar & SOP</span>
            <h2 class="text-lg font-black text-slate-900">Belajar & Mentoring</h2>
        </div>
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#F8F2E4] text-[#8E6920] border border-[#B88E3E]/30">
            SiYota Foundation
        </span>
    </div>

    <!-- 4 Sub-Tabs Navigation (Using Clean Vector Icons) -->
    <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl overflow-x-auto hide-scrollbar text-xs font-bold">
        <a href="{{ route('group.learning.index', ['tab' => 'dokumen']) }}" class="flex-1 py-2 px-2.5 text-center rounded-xl transition-all whitespace-nowrap flex items-center justify-center gap-1.5 {{ $tab === 'dokumen' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 {{ $tab === 'dokumen' ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>SOP & Dokumen</span>
        </a>
        <a href="{{ route('group.learning.index', ['tab' => 'kelas']) }}" class="flex-1 py-2 px-2.5 text-center rounded-xl transition-all whitespace-nowrap flex items-center justify-center gap-1.5 {{ $tab === 'kelas' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 {{ $tab === 'kelas' ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            <span>Kelas</span>
        </a>
        <a href="{{ route('group.learning.index', ['tab' => 'materi']) }}" class="flex-1 py-2 px-2.5 text-center rounded-xl transition-all whitespace-nowrap flex items-center justify-center gap-1.5 {{ $tab === 'materi' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 {{ $tab === 'materi' ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span>Materi</span>
        </a>
        <a href="{{ route('group.learning.index', ['tab' => 'ruangan']) }}" class="flex-1 py-2 px-2.5 text-center rounded-xl transition-all whitespace-nowrap flex items-center justify-center gap-1.5 {{ $tab === 'ruangan' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 {{ $tab === 'ruangan' ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            <span>Zoom & Mentoring</span>
        </a>
    </div>

    <!-- TAB DOKUMEN & SOP: WADAH TERPISAH (SUDAH vs BELUM DIBUKA) -->
    @if($tab === 'dokumen')
    <div class="space-y-5">
        
        <!-- Wadah Filter Status -->
        <div class="grid grid-cols-2 gap-2 text-xs font-bold">
            <button type="button" onclick="switchDocWadah('belum')" id="btn-wadah-belum" class="py-2.5 px-3 rounded-2xl border-2 border-amber-300 bg-amber-50/70 text-amber-900 shadow-2xs flex items-center justify-between transition-all">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Belum Dibuka</span>
                </span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-200 font-extrabold">{{ $unopenedDocuments->count() }}</span>
            </button>

            <button type="button" onclick="switchDocWadah('sudah')" id="btn-wadah-sudah" class="py-2.5 px-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-600 shadow-2xs flex items-center justify-between transition-all">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sudah Dibuka</span>
                </span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-extrabold">{{ $openedDocuments->count() }}</span>
            </button>
        </div>

        <!-- WADAH 1: DOKUMEN BELUM DIBUKA (WAJIB DIPELAJARI KELOMPOK) -->
        <div id="wadah-belum" class="space-y-3">
            <div class="flex items-center justify-between px-1">
                <span class="text-xs font-extrabold text-amber-900 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Wadah: Dokumen Belum Dibuka ({{ $unopenedDocuments->count() }})
                </span>
                <span class="text-[10px] text-slate-400">Klik untuk membaca & mencatat waktu</span>
            </div>

            @forelse($unopenedDocuments as $doc)
            <div class="bg-white rounded-2xl border-2 border-amber-200 p-4 shadow-xs space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-amber-100 text-amber-900">
                                {{ str_replace('_', ' ', $doc->category) }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                Belum Dibuka
                            </span>
                        </div>
                        <h4 class="text-xs font-black text-slate-900 mt-1 leading-snug">{{ $doc->title }}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $doc->summary ?? 'Standar Operasional Prosedur kelompok binaan.' }}</p>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-medium">Ukuran: {{ $doc->file_size_text }}</span>
                    <a href="{{ route('group.learning.open_doc', $doc->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-extrabold text-xs shadow-xs transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <span>Buka & Pelajari</span>
                    </a>
                </div>
            </div>
            @empty
            <div class="p-6 text-center bg-emerald-50/50 rounded-2xl border border-emerald-200 text-xs text-emerald-800 space-y-1">
                <span class="font-extrabold block">Semua Dokumen Telah Dibuka!</span>
                <p class="text-[11px] text-emerald-700">Kelompok Anda telah membuka dan mempelajari seluruh SOP yang tersedia.</p>
            </div>
            @endforelse
        </div>

        <!-- WADAH 2: DOKUMEN SUDAH DIBUKA (LENGKAP DENGAN LOG WAKTU TERAKHIR) -->
        <div id="wadah-sudah" class="space-y-3 hidden">
            <div class="flex items-center justify-between px-1">
                <span class="text-xs font-extrabold text-emerald-900 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Wadah: Dokumen Sudah Dibuka ({{ $openedDocuments->count() }})
                </span>
                <span class="text-[10px] text-slate-400">Tercatat di sistem fasilitator</span>
            </div>

            @forelse($openedDocuments as $doc)
            <div class="bg-white rounded-2xl border-2 border-emerald-200/90 p-4 shadow-xs space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-slate-100 text-slate-700">
                                {{ str_replace('_', ' ', $doc->category) }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">
                                ✓ Sudah Dibuka
                            </span>
                        </div>
                        <h4 class="text-xs font-black text-slate-900 mt-1 leading-snug">{{ $doc->title }}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $doc->summary ?? 'Standar Operasional Prosedur kelompok binaan.' }}</p>
                    </div>
                </div>

                <!-- Log Terakhir Dibuka (Sesuai Permintaan User) -->
                @if(isset($doc->read_log))
                <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200/60 text-[11px] text-emerald-950 space-y-0.5">
                    <div class="flex items-center justify-between font-bold">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Log Terakhir Dibuka:
                        </span>
                        <span class="text-emerald-700 font-extrabold">{{ $doc->read_log->open_count }}x Dibaca</span>
                    </div>
                    <p class="font-medium text-emerald-800">
                        {{ $doc->read_log->opened_at->format('d M Y, H:i') }} WIB 
                        <span class="text-slate-500 text-[10px]">oleh {{ $doc->read_log->reader_name ?? 'Pengurus Kelompok' }}</span>
                    </p>
                </div>
                @endif

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-medium">Ukuran: {{ $doc->file_size_text }}</span>
                    <a href="{{ route('group.learning.open_doc', $doc->id) }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 font-bold text-xs transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Buka Lagi</span>
                    </a>
                </div>
            </div>
            @empty
            <div class="p-6 text-center bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-400">
                Belum ada dokumen yang dibuka oleh kelompok Anda.
            </div>
            @endforelse
        </div>

    </div>
    @endif

    <!-- TAB KELAS -->
    @if($tab === 'kelas')
    <div class="space-y-3">
        @forelse($classes as $item)
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden flex flex-col">
            <div class="h-32 bg-slate-100 relative overflow-hidden">
                <img src="{{ $item->cover_image ?? 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                <div class="absolute top-2 left-2 px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#1E4D2B] text-white">
                    {{ $item->category }}
                </div>
            </div>
            <div class="p-4 space-y-2">
                <h4 class="text-sm font-extrabold text-slate-900">{{ $item->title }}</h4>
                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $item->description }}</p>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                    <span>Pengajar: <b class="text-slate-700">{{ $item->instructor_name }}</b></span>
                    @if($item->zoom_url)
                    <a href="{{ $item->zoom_url }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-[#B88E3E] text-white font-bold hover:bg-[#9E752D]">
                        Ikuti Sesi
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-6 text-center bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-500">
            Belum ada kelas aktif.
        </div>
        @endforelse
    </div>
    @endif

    <!-- TAB MATERI (PDF & VIDEO) -->
    @if($tab === 'materi')
    <div class="space-y-2.5">
        @forelse($materials as $mat)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center shrink-0 text-[#B88E3E]">
                    @if($mat->file_type === 'video')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    @endif
                </div>
                <div class="min-w-0">
                    <h5 class="text-xs font-extrabold text-slate-900 truncate">{{ $mat->title }}</h5>
                    <p class="text-[11px] text-slate-500">{{ strtoupper($mat->file_type) }} • {{ $mat->file_size_text }}</p>
                </div>
            </div>
            <a href="{{ $mat->file_url ?? '#' }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-[#B88E3E] hover:text-white text-slate-700 text-xs font-bold shrink-0 transition-colors">
                Buka
            </a>
        </div>
        @empty
        <div class="p-6 text-center bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-500">
            Belum ada materi pelatihan tersimpan.
        </div>
        @endforelse
    </div>
    @endif

    <!-- TAB RUANGAN (ZOOM & MENTORING) -->
    @if($tab === 'ruangan')
    <div class="space-y-4">
        <div class="space-y-2">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-700">Jadwal Ruangan Online (Zoom)</h3>
            @forelse($schedules as $sc)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                            {{ ucfirst($sc->event_type) }}
                        </span>
                        <h4 class="text-xs font-extrabold text-slate-900 mt-1">{{ $sc->title }}</h4>
                        <p class="text-[11px] text-slate-500">{{ $sc->event_date->format('d M Y') }} • {{ $sc->time_info }}</p>
                    </div>
                    @if($sc->location_or_link)
                    <a href="{{ $sc->location_or_link }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shrink-0">
                        Masuk Zoom
                    </a>
                    @endif
                </div>
                @if($sc->description)
                <p class="text-xs text-slate-600 bg-slate-50 p-2 rounded-xl leading-relaxed">{{ $sc->description }}</p>
                @endif
            </div>
            @empty
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-400">
                Belum ada jadwal pertemuan tatap muka atau zoom.
            </div>
            @endforelse
        </div>

        <div class="space-y-2 pt-2 border-t border-slate-100">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#1E4D2B]">Hasil Mentoring Lapangan</h3>
            @forelse($mentoringNotes as $note)
            <div class="bg-emerald-50/50 border border-emerald-200/80 rounded-2xl p-3.5 space-y-2">
                <div class="flex items-center justify-between text-[11px]">
                    <span class="font-extrabold text-emerald-900">{{ $note->topic }}</span>
                    <span class="text-slate-400">{{ $note->date->format('d M Y') }}</span>
                </div>
                <p class="text-xs text-slate-700 leading-relaxed">{{ $note->observations }}</p>
                @if($note->action_items)
                <div class="bg-white p-2 rounded-xl border border-emerald-200/50 text-xs text-slate-800">
                    <b class="text-emerald-800">Tindak Lanjut:</b> {{ $note->action_items }}
                </div>
                @endif
            </div>
            @empty
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-400">
                Belum ada catatan mentoring lapangan dari fasilitator.
            </div>
            @endforelse
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
    function switchDocWadah(type) {
        const wadahBelum = document.getElementById('wadah-belum');
        const wadahSudah = document.getElementById('wadah-sudah');
        const btnBelum = document.getElementById('btn-wadah-belum');
        const btnSudah = document.getElementById('btn-wadah-sudah');

        if (type === 'belum') {
            wadahBelum.classList.remove('hidden');
            wadahSudah.classList.add('hidden');
            btnBelum.className = 'py-2.5 px-3 rounded-2xl border-2 border-amber-300 bg-amber-50/70 text-amber-900 shadow-2xs flex items-center justify-between transition-all';
            btnSudah.className = 'py-2.5 px-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-600 shadow-2xs flex items-center justify-between transition-all';
        } else {
            wadahBelum.classList.add('hidden');
            wadahSudah.classList.remove('hidden');
            btnSudah.className = 'py-2.5 px-3 rounded-2xl border-2 border-emerald-400 bg-emerald-50/70 text-emerald-900 shadow-2xs flex items-center justify-between transition-all';
            btnBelum.className = 'py-2.5 px-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-600 shadow-2xs flex items-center justify-between transition-all';
        }
    }
</script>
@endpush
@endsection
