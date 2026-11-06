@extends('group.layouts.app')

@section('title', 'Profil & Anggota Kelompok — ' . ($group->name ?? 'KWT Sumber Rejeki'))

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold tracking-wider uppercase text-[#B88E3E]">PROFIL KELOMPOK BINAAN</span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ ucfirst($group->status ?? 'Aktif') }}
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                {{ $group->name ?? 'KWT Sumber Rejeki' }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                {{ $group->category ?? 'Kelompok Binaan SiYota' }} • {{ $group->regency ?? 'Kabupaten Tasikmalaya' }}
            </p>
        </div>

        <!-- Single Group Account Rule Badge -->
        <div class="p-3 rounded-2xl bg-[#FDFBF7] border border-[#E6CE93]/60 text-xs text-slate-600 max-w-sm">
            <span class="font-bold text-[#B88E3E]">Prinsip 1 Kelompok:</span> Satu akun utama kelompok dengan hak akses bersama untuk ketua, pengelola kas, dan pengelola produk.
        </div>
    </div>

    <!-- Group Information Card -->
    <div class="ruma-card rounded-3xl p-6 sm:p-7 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase">Nama Kelompok Usaha</span>
                <div class="text-base font-extrabold text-slate-900">{{ $group->name }}</div>
                <div class="text-xs text-slate-500">{{ $group->category }}</div>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase">Wilayah & Lokasi</span>
                <div class="text-base font-extrabold text-slate-900">{{ $group->district ?? 'Singaparna' }}, {{ $group->regency }}</div>
                <div class="text-xs text-slate-500">{{ $group->address }}</div>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase">Fasilitator Pendamping SiYota</span>
                <div class="text-base font-extrabold text-[#1E4D2B] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>{{ $facilitator->name ?? 'Kang Rian Prasetya' }}</span>
                </div>
                <div class="text-xs text-slate-500">Pendampingan Intensif Wilayah Priangan</div>
            </div>

        </div>
    </div>

    <!-- PENGELOLA KELOMPOK (Sesuai Brief: Ibu Siti Ketua/Admin, Ibu Rina Keuangan, Ibu Dewi Produk) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengelola & Anggota Berhak Akses</h3>
                <p class="text-xs text-slate-500">Akses akun kelompok dibagi berdasarkan peran tanggung jawab di lapangan.</p>
            </div>
            <button type="button" onclick="alert('Fitur undang anggota: Masukkan nomor WhatsApp atau email pengurus untuk diberikan izin akses.')" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:border-[#B88E3E] hover:text-[#B88E3E] transition-all">
                + Tambah Anggota
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($members as $m)
                <div class="ruma-card ruma-card-hover rounded-3xl p-5 flex flex-col justify-between space-y-4 group">
                    <div class="flex items-center gap-4">
                        <img src="{{ $m->avatar_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $m->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100 group-hover:scale-105 transition-transform flex-shrink-0">
                        <div class="min-w-0">
                            <h4 class="font-extrabold text-slate-900 text-base truncate">
                                {{ $m->name }}
                            </h4>
                            <div class="text-xs font-semibold text-[#B88E3E] truncate">
                                {{ $m->position_title ?? 'Anggota Kelompok' }}
                            </div>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $m->role === 'group_admin' ? 'bg-amber-100 text-[#B88E3E]' : 'bg-slate-100 text-slate-600' }}">
                                {{ $m->role === 'group_admin' ? 'Admin Kelompok' : 'Anggota Berizin' }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-500">
                        <div class="flex items-center justify-between">
                            <span>Email Akun:</span>
                            <strong class="text-slate-700 truncate max-w-[150px]">{{ $m->email }}</strong>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Kontak HP:</span>
                            <strong class="text-slate-700">{{ $m->phone ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="pt-1">
                        <a href="{{ route('switch.persona', $m->email === 'siti@ruma.siyota.org' ? 'ibu_siti' : ($m->email === 'rina@ruma.siyota.org' ? 'ibu_rina' : 'ibu_dewi')) }}" class="block text-center py-2 rounded-xl bg-slate-50 hover:bg-[#FDFBF7] text-xs font-bold text-slate-700 hover:text-[#B88E3E] border border-slate-200 transition-colors">
                            Masuk Sebagai {{ explode(' ', $m->name)[0] }} {{ explode(' ', $m->name)[1] ?? '' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Alur Pendaftaran & Status Akun Guide -->
    <div class="ruma-card rounded-3xl p-6 sm:p-7 space-y-4">
        <h3 class="font-extrabold text-slate-900 text-base">Alur Pendaftaran & Verifikasi Kelompok RUMA</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs text-slate-600">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <div class="font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-800 flex items-center justify-center text-[10px]">1</span>
                    <span>Pendaftaran Web</span>
                </div>
                <p class="leading-relaxed">Kelompok mengisi nama, wilayah, komoditas, dan kontak ketua melalui website.</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <div class="font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-800 flex items-center justify-center text-[10px]">2</span>
                    <span>Verifikasi SiYota</span>
                </div>
                <p class="leading-relaxed">Admin & Fasilitator SiYota memvalidasi data dan kesiapan usaha kelompok.</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                <div class="font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-800 flex items-center justify-center text-[10px]">3</span>
                    <span>Aktivasi Akun</span>
                </div>
                <p class="leading-relaxed">Akun kelompok diaktifkan dengan status <em>Aktif</em> dan ditugaskan fasilitator.</p>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50/70 border border-[#E6CE93]/60 space-y-1.5">
                <div class="font-bold text-[#B88E3E] flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#B88E3E] text-white flex items-center justify-center text-[10px]">4</span>
                    <span>Pendampingan Rutin</span>
                </div>
                <p class="text-amber-900 leading-relaxed">Kelompok mengelola kas, produk, kelas, dan tugas marketing berkala.</p>
            </div>
        </div>
    </div>

</div>
@endsection

