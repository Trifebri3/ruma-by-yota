@extends('layouts.app')

@section('title', 'Daftar Akun Baru — RUMA SiYota')

@section('content')
<div class="max-w-lg mx-auto py-4 sm:py-8 space-y-6">

    <!-- Header -->
    <div class="text-center space-y-2">
        <img src="{{ asset('logo.png') }}" alt="RUMA SiYota" class="w-16 h-16 object-contain mx-auto">
        <div class="flex items-center justify-center gap-1.5 pt-1">
            <span class="font-extrabold text-xl tracking-tight text-slate-900">RUMA</span>
            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-[#F8F2E4] text-[#B88E3E] border border-[#E6CE93]/60">Kelompok Tani</span>
        </div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">
            Pendaftaran Akun Baru
        </h1>
        <p class="text-xs text-slate-500">
            Daftarkan akun kelompok tani / usaha hasil bumi Anda untuk pendampingan SiYota.org.
        </p>
    </div>

    <!-- 1. DAFTAR DENGAN GOOGLE (Sesuai Permintaan) -->
    <div>
        <a href="{{ route('auth.google') }}" class="w-full py-3.5 px-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-400 hover:bg-slate-50/80 shadow-xs flex items-center justify-center gap-3 transition-all font-bold text-xs sm:text-sm text-slate-700 hover:scale-[1.01] active:scale-[0.99]">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Daftar Cepat dengan Google</span>
        </a>
    </div>

    <!-- Divider Atau -->
    <div class="relative flex items-center justify-center">
        <div class="border-t border-slate-200 w-full"></div>
        <span class="bg-white px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 absolute">
            atau lengkapi formulir pendaftaran
        </span>
    </div>

    <!-- 2. FORM PENDAFTARAN -->
    <div class="ruma-card rounded-3xl p-6 sm:p-8 space-y-5">
        @if($errors->any())
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4" id="reg-form">
            @csrf
            <input type="hidden" name="role" value="user">

            <!-- Info Jenis Akun -->
            <div class="p-3.5 rounded-2xl bg-[#FDFBF7] border border-[#B88E3E]/30 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#8E6920] block">Jenis Pendaftaran Akun:</span>
                    <span class="text-xs font-black text-slate-900">Akun Kelompok Tani / Usaha Hasil Bumi (USER)</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#B88E3E] text-white">
                    USER
                </span>
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Siti Khodijah" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
            </div>

            <!-- Nama Kelompok -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama Kelompok Tani / KWT (Opsional)
                </label>
                <input type="text" name="group_name" value="{{ old('group_name') }}" placeholder="Contoh: KWT Sumber Rejeki (kosongkan jika bergabung ke kelompok yang ada)" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
            </div>

            <!-- Email & WhatsApp -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Nomor HP / WhatsApp
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="0812-xxxx-xxxx" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <!-- Password & Konfirmasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Konfirmasi Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-black text-sm shadow-md shadow-[#B88E3E]/20 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                    <span>Daftarkan Akun Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Login Link -->
    <div class="text-center text-xs text-slate-500">
        Sudah memiliki akun? 
        <a href="{{ route('login') }}" class="font-bold text-[#B88E3E] hover:underline">
            Masuk ke Akun Anda →
        </a>
    </div>

</div>
@endsection

