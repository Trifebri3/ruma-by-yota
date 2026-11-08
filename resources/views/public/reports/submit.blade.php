@extends('layouts.app')

@section('title', 'Laporan Progress Harian — ' . $link->group->name . ' | RUMA SiYota')

@section('content')
<div class="max-w-xl mx-auto py-4 sm:py-8 space-y-6">

    <!-- Top Card: Identitas Kelompok & Tautan -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="RUMA" class="w-8 h-8 object-contain">
                <div>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-[#F8F2E4] text-[#B88E3E] border border-[#E6CE93]/60">
                        Daily Progress Report
                    </span>
                </div>
            </div>
            <span class="text-[11px] font-bold text-slate-500">
                Zona Waktu: WIB (Asia/Jakarta)
            </span>
        </div>

        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Project / Kelompok Binaan:</span>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                {{ $link->group->name }}
            </h1>
            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                <span>{{ $link->group->regency }}</span>
                @if($link->group->commodity_summary)
                    <span>&bull;</span>
                    <span class="text-[#1E4D2B] font-bold">{{ $link->group->commodity_summary }}</span>
                @endif
            </div>
        </div>

        <!-- Judul & Instruksi Laporan -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
            <h2 class="text-sm font-extrabold text-slate-800">
                {{ $link->title }}
            </h2>
            @if($link->description)
                <p class="text-xs text-slate-600 leading-relaxed">
                    {{ $link->description }}
                </p>
            @endif
        </div>

        <!-- Status Kuota Harian & Tanggal Otomatis -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
            <div class="p-3 rounded-2xl border border-slate-100 bg-white">
                <span class="text-[10px] font-bold uppercase text-slate-400 block">Tanggal Laporan (Otomatis)</span>
                <span class="text-xs font-black text-slate-900">
                    {{ $today->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>

            <div class="p-3 rounded-2xl border {{ $isQuotaFull ? 'border-amber-200 bg-amber-50/70 text-amber-900' : 'border-slate-100 bg-white' }}">
                <span class="text-[10px] font-bold uppercase {{ $isQuotaFull ? 'text-amber-800' : 'text-slate-400' }} block">Status Kuota Laporan Hari Ini</span>
                <div class="flex items-center gap-1.5 text-xs font-black">
                    @if($link->daily_limit > 0)
                        @if($isQuotaFull)
                            <span class="text-rose-600">Kuota Penuh ({{ $todayReportsCount }}/{{ $link->daily_limit }} laporan)</span>
                        @else
                            <span class="text-emerald-700">Tersedia (Laporan ke-{{ $todayReportsCount + 1 }} dari maks {{ $link->daily_limit }})</span>
                        @endif
                    @else
                        <span class="text-emerald-700">Tidak Dibatasi (Bebas Kirim)</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Alert Status Tautan -->
    @if(! $isLinkActive)
        <div class="p-4 rounded-3xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1">
            <div class="font-extrabold text-sm flex items-center gap-1.5 text-rose-900">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Tautan Laporan Ditutup</span>
            </div>
            <p>
                Tautan laporan ini sedang dinonaktifkan oleh administrator atau periode pelaporan telah berakhir. Silakan hubungi fasilitator atau pengurus kelompok.
            </p>
        </div>
    @elseif($isQuotaFull)
        <div class="p-5 rounded-3xl bg-amber-50 border border-amber-200 text-amber-900 text-xs space-y-2">
            <div class="font-black text-sm flex items-center gap-2 text-amber-950">
                <svg class="w-5 h-5 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Batas Laporan Hari Ini Telah Tercapai</span>
            </div>
            <p class="leading-relaxed">
                Kelompok telah mengirimkan <strong>{{ $todayReportsCount }} laporan</strong> hari ini (batas maksimal: <strong>{{ $link->daily_limit }} laporan per hari</strong>).
            </p>
            <p class="text-[11px] text-amber-800 font-semibold">
                Sistem akan secara otomatis membuka kembali kuota pengiriman laporan pada hari berikutnya (pukul 00:00 WIB). Laporan Anda sebelumnya telah aman tersimpan di sistem.
            </p>
        </div>
    @endif

    <!-- Formulir Pengisian Laporan -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-7 shadow-xs space-y-5">
        
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-slate-900">
                Formulir Laporan Aktivitas & Progress
            </h3>
            <p class="text-xs text-slate-500">
                Lengkapi formulir di bawah ini dengan ringkas, jelas, dan sertakan foto dokumentasi.
            </p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('public.report.submit', $link->token) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Project / Kelompok (Otomatis) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Project / Kelompok
                </label>
                <input type="text" readonly value="{{ $link->group->name }} ({{ $link->group->regency }})" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 font-bold focus:outline-none cursor-not-allowed">
            </div>

            <!-- Nama Pelapor -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama Pelapor <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="reporter_name" value="{{ old('reporter_name') }}" required 
                    placeholder="Contoh: Budi Santoso / Pak Mamat"
                    {{ (! $isLinkActive || $isQuotaFull) ? 'disabled' : '' }}
                    class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E] disabled:bg-slate-50 disabled:text-slate-400">
                <span class="text-[10px] text-slate-400 mt-1 block">Tulis nama lengkap atau panggilan Anda di kelompok</span>
            </div>

            <!-- Judul Laporan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Judul Laporan Progress <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required 
                    placeholder="Contoh: Hari Pertama - Penyiapan Bedengan dan Semai Bibit"
                    {{ (! $isLinkActive || $isQuotaFull) ? 'disabled' : '' }}
                    class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E] disabled:bg-slate-50 disabled:text-slate-400">
            </div>

            <!-- Deskripsi Kegiatan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Deskripsi Aktivitas & Capaian <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" rows="4" required 
                    placeholder="Ceritakan ringkasan aktivitas hari ini, capaian yang dicapai, kendala di lapangan, atau kebutuhan pendampingan..."
                    {{ (! $isLinkActive || $isQuotaFull) ? 'disabled' : '' }}
                    class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E] leading-relaxed disabled:bg-slate-50 disabled:text-slate-400">{{ old('description') }}</textarea>
                <span class="text-[10px] text-slate-400 mt-1 block">Minimal 10 karakter</span>
            </div>

            <!-- Foto Dokumentasi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Upload Foto Dokumentasi Kegiatan
                </label>
                <div class="mt-1 flex justify-center px-4 pt-4 pb-5 border-2 border-slate-200 border-dashed rounded-2xl hover:border-slate-300 transition-colors bg-slate-50/50">
                    <div class="space-y-2 text-center">
                        <!-- Preview container -->
                        <div id="previewContainer" class="hidden mb-2">
                            <img id="imagePreview" src="" alt="Pratinjau Foto" class="max-h-48 mx-auto rounded-xl object-contain border border-slate-200 shadow-xs">
                        </div>
                        <div id="uploadPlaceholder" class="flex flex-col items-center">
                            <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div class="flex text-xs text-slate-600 font-bold mt-1">
                                <label for="photo" class="relative cursor-pointer text-[#B88E3E] hover:underline">
                                    <span>Pilih foto dari kamera / galeri</span>
                                    <input id="photo" name="photo" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)" {{ (! $isLinkActive || $isQuotaFull) ? 'disabled' : '' }}>
                                </label>
                            </div>
                            <p class="text-[10px] text-slate-400">JPG, PNG, atau WEBP (Maksimal 5 MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                @if($isLinkActive && ! $isQuotaFull)
                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-black text-sm shadow-md shadow-[#B88E3E]/20 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                        <span>Kirim Laporan Progress Hari Ini</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                @else
                    <button type="button" disabled class="w-full py-3.5 rounded-2xl bg-slate-200 text-slate-400 font-black text-sm cursor-not-allowed">
                        Pengiriman Laporan Tidak Tersedia Saat Ini
                    </button>
                @endif
            </div>

        </form>

    </div>

    <!-- Riwayat Laporan Hari Ini (Transparansi Kelompok) -->
    @if($todayReports->count() > 0)
        <div class="bg-white rounded-3xl border border-slate-200/90 p-5 shadow-xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-xs font-black text-slate-900">
                    Laporan Terkirim Hari Ini ({{ $todayReports->count() }})
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase">
                    {{ $today->locale('id')->isoFormat('D MMMM Y') }}
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($todayReports as $idx => $rep)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-600 font-extrabold text-[10px] flex items-center justify-center">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <span class="font-extrabold text-slate-800">{{ $rep->title }}</span>
                                <div class="text-[10px] text-slate-400">
                                    Oleh: <strong>{{ $rep->reporter_name }}</strong> &bull; {{ $rep->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                            ✓ Terkirim
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Back to Public Web -->
    <div class="text-center pt-2">
        <a href="{{ route('public.home') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">
            &larr; Kembali ke Beranda RUMA SiYota
        </a>
    </div>

</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('previewContainer');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection

