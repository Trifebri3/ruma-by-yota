@extends('group.layouts.app')

@section('title', 'Profil & Pengaturan Kelompok — RUMA')

@section('content')
<div class="space-y-5">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Akun & Pengaturan</span>
            <h2 class="text-lg font-black text-slate-900">Profil Kelompok</h2>
        </div>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-[#1E4D2B] text-white">
            Terverifikasi
        </span>
    </div>

    <!-- Group Card Overview -->
    <div class="bg-gradient-to-br from-[#FDFBF7] to-[#F8F2E4] rounded-2xl p-4 border border-[#B88E3E]/20 shadow-xs flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-white p-1 border border-amber-200/60 shadow-xs overflow-hidden shrink-0">
            <img src="{{ $group->business_logo_url ?? ($group->logo_url ?? 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=200&q=80') }}" alt="{{ $group->name }}" class="w-full h-full object-contain">
        </div>
        <div class="min-w-0">
            <h3 class="text-sm font-black text-slate-900 truncate">{{ $group->name }}</h3>
            <p class="text-xs font-bold text-[#8E6920]">{{ $group->business_name ?? 'Merek Belum Diatur' }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">{{ $group->regency }}</p>
        </div>
    </div>

    <!-- Form Update Profile & Visibility -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-4">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-2">
            Identitas & Pengaturan Publik
        </h3>

        <form action="{{ route('group.account.update') }}" method="POST" class="space-y-3 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Merek Usaha Kelompok</label>
                <input type="text" name="business_name" value="{{ old('business_name', $group->business_name) }}" placeholder="Contoh: Sambal Pasir Honje Berkah" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama PIC / Kontak Utama</label>
                <input type="text" name="pic_name" value="{{ old('pic_name', $group->pic_name ?? $group->leader_name) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">No. WhatsApp Kelompok *</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $group->contact_phone) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat Usaha</label>
                <textarea name="public_description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('public_description', $group->public_description) }}</textarea>
            </div>

            <!-- Privacy & Visibility Checkboxes -->
            <div class="bg-slate-50 p-3 rounded-xl space-y-2 border border-slate-100">
                <span class="block text-[11px] font-extrabold text-slate-700">Pengaturan Tampilan Web Publik:</span>
                
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_public" value="1" {{ $group->is_public ? 'checked' : '' }} class="rounded text-[#B88E3E] focus:ring-[#B88E3E]">
                    <span class="text-xs text-slate-700">Tampilkan Profil Kelompok di Publik (ruma.siyota.org)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="pic_public_visible" value="1" {{ $group->pic_public_visible ? 'checked' : '' }} class="rounded text-[#B88E3E] focus:ring-[#B88E3E]">
                    <span class="text-xs text-slate-700">Tampilkan Nama PIC di Kartu Publik</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-extrabold text-xs shadow-xs transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Internal Members Management (Pengurus Kelompok) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Pengurus Kelompok</h3>
                <p class="text-[10px] text-slate-400">Data internal pengelola usaha kelompok</p>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                1 Akun Bersama
            </span>
        </div>

        <div class="space-y-2.5">
            @foreach($defaultMembers as $mem)
            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50/70 border border-slate-100">
                <img src="{{ $mem['avatar'] }}" alt="{{ $mem['name'] }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-white shadow-xs">
                <div class="min-w-0 flex-1">
                    <h5 class="text-xs font-extrabold text-slate-900">{{ $mem['name'] }}</h5>
                    <p class="text-[10px] font-bold text-[#8E6920]">{{ $mem['role'] }}</p>
                    <p class="text-[10px] text-slate-400">{{ $mem['phone'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Fasilitator Pendamping Info -->
    <div class="bg-[#1E4D2B]/5 rounded-2xl p-4 border border-[#1E4D2B]/20 space-y-2 text-xs">
        <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#1E4D2B] block">Fasilitator Pendamping Anda</span>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-[#1E4D2B] text-white flex items-center justify-center font-bold text-xs">
                SY
            </div>
            <div>
                <h5 class="font-bold text-slate-900">Rian Pratama</h5>
                <p class="text-[11px] text-slate-600">Fasilitator Wilayah Tasikmalaya • SiYota.org</p>
            </div>
        </div>
    </div>

</div>
@endsection

