@extends('group.layouts.app')

@section('title', 'Laporan Progress Harian — ' . ($group->name ?? 'Kelompok'))

@section('content')
<div class="space-y-4">

    <!-- Top Card / Header -->
    <div class="bg-gradient-to-br from-[#FDFBF7] to-[#F8F2E4] rounded-2xl p-4 border border-[#B88E3E]/20 shadow-xs space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#8E6920]">DAILY PROGRESS REPORT</span>
            <span class="text-[10px] font-bold text-slate-500">{{ $today->locale('id')->isoFormat('D MMMM Y') }}</span>
        </div>
        <h2 class="text-base font-black text-slate-900 leading-tight">
            Laporan Progress Harian
        </h2>
        <p class="text-xs text-slate-600 leading-relaxed">
            Laporkan perkembangan harian kelompok atau bagikan tautan cepat ke anggota di lapangan tanpa perlu login.
        </p>
    </div>

    <!-- Tautan Publik untuk Dibagikan ke Lapangan (WhatsApp Share) -->
    @if($links->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black text-slate-900 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#B88E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <span>Tautan Pengisian Cepat (Tanpa Login)</span>
                </span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                    Aktif
                </span>
            </div>

            @foreach($links as $link)
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="text-xs font-bold text-slate-800">{{ $link->title }}</div>
                            <div class="text-[11px] text-slate-500 font-medium">
                                Kuota: <strong>{{ $link->daily_limit > 0 ? $link->daily_limit . ' laporan/hari' : 'Tidak dibatasi' }}</strong>
                                &bull; Terkirim hari ini: <strong>{{ $link->todayReportsCount() }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <button type="button" onclick="copyLink('{{ $link->public_url }}', this)" class="flex-1 py-2 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-[11px] flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span>Salin Tautan</span>
                        </button>

                        @php
                            $waText = urlencode("Halo rekan-rekan " . $group->name . ", silakan kirimkan laporan progress kegiatan hari ini melalui tautan resmi RUMA SiYota berikut:\n" . $link->public_url);
                        @endphp
                        <a href="https://api.whatsapp.com/send?text={{ $waText }}" target="_blank" class="py-2 px-3 rounded-xl bg-[#1E4D2B] hover:bg-[#163820] text-white font-bold text-[11px] flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                            <span>Kirim ke WhatsApp</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Form Kirim Laporan Langsung -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3">
        <div class="border-b border-slate-100 pb-2 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">
                Formulir Laporan Baru
            </h3>
            <span class="text-[10px] font-bold text-slate-400">
                Oleh: {{ Auth::user()->name }}
            </span>
        </div>

        <form method="POST" action="{{ route('group.progress.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf

            <div>
                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">
                    Judul Aktivitas Hari Ini <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required 
                    placeholder="Contoh: Pemupukan Organik & Pembersihan Gulma Lahan A" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">
                    Deskripsi Aktivitas & Capaian <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" rows="3" required 
                    placeholder="Tuliskan perkembangan hari ini, kondisi cuaca, jumlah bibit, atau kendala yang dihadapi..." 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E] leading-relaxed">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">
                    Foto Dokumentasi Kegiatan
                </label>
                <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-extrabold file:bg-[#F8F2E4] file:text-[#B88E3E] hover:file:bg-[#E6CE93]/40">
            </div>

            <div class="pt-1">
                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-extrabold text-xs shadow-sm shadow-[#B88E3E]/20 transition-all flex items-center justify-center gap-2">
                    <span>Simpan Laporan Progress</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Riwayat Laporan Progress Kelompok -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">
                Riwayat Laporan Kelompok
            </h3>
            <span class="text-[10px] font-bold text-slate-400">
                Total: {{ $reports->total() }} laporan
            </span>
        </div>

        <div class="space-y-3">
            @forelse($reports as $report)
                <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block">
                                {{ \Carbon\Carbon::parse($report->report_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </span>
                            <h4 class="text-xs font-black text-slate-900 leading-snug mt-0.5">
                                {{ $report->title }}
                            </h4>
                        </div>
                        @if($report->status === 'approved')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-800">
                                Disetujui
                            </span>
                        @elseif($report->status === 'reviewed')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-blue-100 text-blue-800">
                                Ditinjau
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-600">
                                Terkirim
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                        {{ $report->description }}
                    </p>

                    @if($report->photo_url)
                        <div class="rounded-xl overflow-hidden border border-slate-200 aspect-video bg-slate-100">
                            <img src="{{ asset($report->photo_url) }}" alt="{{ $report->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-1 border-t border-slate-100 text-[10px] text-slate-400 font-medium">
                        <span>Pelapor: <strong class="text-slate-700">{{ $report->reporter_name }}</strong></span>
                        <span>{{ $report->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                    </div>

                    @if($report->admin_notes)
                        <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-amber-900">
                            <strong>Catatan Fasilitator:</strong> {{ $report->admin_notes }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200/90 p-8 text-center text-slate-400 text-xs">
                    Belum ada laporan progress yang tercatat untuk kelompok ini.
                </div>
            @endforelse
        </div>

        @if($reports->hasPages())
            <div class="pt-2">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    function copyLink(text, buttonElement) {
        navigator.clipboard.writeText(text).then(function() {
            const originalHtml = buttonElement.innerHTML;
            buttonElement.innerHTML = '<span>✓ Tautan Disalin!</span>';
            buttonElement.classList.add('bg-emerald-600');
            setTimeout(function() {
                buttonElement.innerHTML = originalHtml;
                buttonElement.classList.remove('bg-emerald-600');
            }, 2000);
        }).catch(function() {
            prompt('Salin tautan ini:', text);
        });
    }
</script>
@endpush
@endsection

