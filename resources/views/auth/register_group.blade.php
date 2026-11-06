@extends('layouts.app')

@section('title', 'Pendaftaran Kelompok Usaha Tani Baru — RUMA SiYota')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-50 text-[#B88E3E] border border-[#E6CE93]/60">
            <span>PROGRAM PENDAMPINGAN SIYOTA</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Pendaftaran Kelompok Usaha Hasil Bumi
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto">
            Daftarkan kelompok tani, KWT, atau usaha bersama hasil bumi Anda untuk mendapatkan pendampingan terstruktur, kelas pelatihan, dan pembinaan mutu produk dari SiYota.org.
        </p>
    </div>

    <!-- Info Alur Pendaftaran -->
    <div class="p-4 rounded-3xl bg-[#FDFBF7] border border-[#E6CE93]/60 flex items-start gap-3.5">
        <div class="w-9 h-9 rounded-xl bg-amber-100 text-[#B88E3E] flex items-center justify-center font-bold flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="text-xs text-slate-600 space-y-1">
            <strong class="text-slate-800">Alur Verifikasi Pendaftaran:</strong>
            <p>Setelah form dikirim, data kelompok berstatus <em>Menunggu Verifikasi</em>. Tim Admin SiYota akan menghubungi pengurus untuk validasi sebelum akun kelompok diaktifkan.</p>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="ruma-card rounded-3xl p-6 sm:p-8 space-y-6">
        <form action="{{ route('register.group.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Kelompok -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama Kelompok Usaha / Tani <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" required placeholder="Contoh: KWT Sumber Rejeki, Kelompok Tani Mandiri" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E] focus:ring-2 focus:ring-[#B88E3E]/20">
            </div>

            <!-- Wilayah -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Kabupaten / Kota <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="regency" required placeholder="Contoh: Kabupaten Tasikmalaya" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Kecamatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="district" required placeholder="Contoh: Singaparna" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <!-- Desa & Alamat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Desa / Kelurahan
                    </label>
                    <input type="text" name="village" placeholder="Contoh: Sukarame" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Komoditas Utama Kelompok <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="commodity_summary" required placeholder="Contoh: Cabai, Aren, Sayuran Organik" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <!-- Kontak Pengurus -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Nama Ketua / Pengurus Utama <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="leader_name" required placeholder="Nama lengkap ketua" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Nomor HP / WhatsApp Aktif <span class="text-rose-500">*</span>
                    </label>
                    <input type="tel" name="contact_phone" required placeholder="0812-xxxx-xxxx" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Alamat Lengkap / Sekretariat Kelompok
                </label>
                <textarea name="address" rows="2" placeholder="Nama kampung, nomor RT/RW, patokan..." class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]"></textarea>
            </div>

            <!-- Catatan Potensi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Ceritakan Singkat Usaha & Jumlah Anggota
                </label>
                <textarea name="notes" rows="2" placeholder="Contoh: Kami memiliki 15 anggota aktif yang memproduksi gula semut aren..." class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button type="submit" class="w-full py-4 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-extrabold text-sm shadow-md shadow-[#B88E3E]/20 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                    <span>Ajukan Pendaftaran Kelompok</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

