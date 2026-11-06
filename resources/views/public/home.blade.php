@extends('layouts.app')

@section('title', 'RUMA — Ruang Usaha Masyarakat Alam | SiYota.org')

@section('content')
<div class="space-y-12 pb-16">

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-[#FDFBF7] via-white to-white rounded-3xl p-6 sm:p-10 border border-amber-100/60 shadow-sm overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-gradient-to-br from-[#B88E3E]/10 to-[#1E4D2B]/10 blur-3xl pointer-events-none"></div>
        <div class="max-w-3xl relative z-10 space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#F8F2E4] border border-[#B88E3E]/30 text-xs font-bold text-[#8E6920]">
                <span class="w-2 h-2 rounded-full bg-[#B88E3E] animate-pulse"></span>
                Platform Pendampingan Petani & Kelompok Usaha Hasil Bumi
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Ruang Usaha <br class="hidden sm:inline">
                <span class="text-[#B88E3E]">Masyarakat Alam</span>
            </h1>
            
            <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                Menghubungkan tim fasilitator <strong>SiYota.org</strong> dengan kelompok petani dan usaha lokal. Belajar bersama, kelola lahan & komoditas, tingkatkan mutu produk, dan kembangkan pemasaran secara terarah.
            </p>

            <div class="flex flex-wrap items-center gap-3 pt-3">
                <a href="{{ route('register.group') }}" class="px-6 py-3.5 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-bold text-sm shadow-md shadow-[#B88E3E]/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Daftarkan Kelompok Tani
                </a>
                <a href="{{ route('login') }}" class="px-6 py-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm border border-slate-200 transition-all flex items-center gap-2">
                    Masuk ke Dashboard
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Public Groups Showcase -->
    <section id="kelompok-binaan" class="space-y-6 scroll-mt-24">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 border-b border-slate-100 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#1E4D2B]">Potensi Lokal Terpilih</span>
                <h2 class="text-2xl font-black text-slate-900">Kelompok Binaan SiYota</h2>
            </div>
            <p class="text-xs text-slate-500 font-medium">Transparan, mandiri, dan berdaya bersama sumber daya alam lokal</p>
        </div>

        <!-- Search & Filter Bar -->
        <form action="{{ route('public.home') }}#kelompok-binaan" method="GET" class="flex flex-col sm:flex-row items-center gap-2 text-xs">
            <div class="relative flex-1 w-full">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kelompok, komoditas (cabai, aren), atau nama produk..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-[#B88E3E]">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <div class="w-full sm:w-56">
                <select name="regency" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium focus:outline-none focus:border-[#B88E3E]">
                    <option value="">Semua Wilayah</option>
                    @foreach($regencies as $reg)
                    <option value="{{ $reg }}" {{ request('regency') === $reg ? 'selected' : '' }}>{{ $reg }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#1E4D2B] hover:bg-[#143D24] text-white font-bold transition-colors">
                Cari
            </button>

            @if(request()->filled('q') || request()->filled('regency'))
            <a href="{{ route('public.home') }}#kelompok-binaan" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold transition-colors">
                Reset
            </a>
            @endif
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($groups as $group)
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md hover:border-[#B88E3E]/50 transition-all overflow-hidden flex flex-col group">
                <!-- Group Image Banner -->
                <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                    <img src="{{ $group->banner_url ?? 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $group->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    
                    <!-- Category Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#1E4D2B] text-white shadow-sm">
                            Binaan SiYota
                        </span>
                    </div>

                    <!-- Business Logo Avatar -->
                    @if($group->business_logo_url || $group->logo_url)
                    <div class="absolute -bottom-4 right-4 w-12 h-12 rounded-xl bg-white p-1 shadow-md border border-slate-100 overflow-hidden">
                        <img src="{{ $group->business_logo_url ?? $group->logo_url }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
                    </div>
                    @endif

                    <div class="absolute bottom-3 left-3 text-white">
                        <span class="text-xs font-medium text-amber-200 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ $group->regency }}
                        </span>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-[#B88E3E] transition-colors">
                            {{ $group->name }}
                        </h3>
                        @if($group->business_name)
                            <p class="text-xs font-semibold text-[#8E6920]">{{ $group->business_name }}</p>
                        @endif

                        <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                            {{ $group->public_description ?? 'Kelompok usaha berbasis pertanian andalan dan olahan hasil bumi terpadu.' }}
                        </p>
                    </div>

                    <!-- Meta Specs -->
                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Komoditas:</span>
                            <span class="font-bold text-slate-800">{{ $group->commodity_summary ?? 'Hasil Bumi' }}</span>
                        </div>
                        @if($group->pic_public_visible && $group->pic_name)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">PIC Kelompok:</span>
                            <span class="font-bold text-slate-800">{{ $group->pic_name }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 flex items-center gap-2">
                        <a href="{{ route('public.groups.show', $group->slug) }}" class="flex-1 py-2 px-3 rounded-xl bg-slate-50 hover:bg-[#F8F2E4] hover:text-[#8E6920] border border-slate-200 text-xs font-bold text-center text-slate-700 transition-colors">
                            Profil Kelompok
                        </a>
                        @if($group->business_name)
                        <a href="{{ route('public.businesses.show', $group->slug) }}" class="py-2 px-3 rounded-xl bg-[#B88E3E]/10 hover:bg-[#B88E3E] hover:text-white border border-[#B88E3E]/30 text-xs font-bold text-[#8E6920] transition-colors">
                            Katalog Produk
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
                <p class="text-sm text-slate-500 font-medium">Belum ada kelompok yang dipublikasikan.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Public Land & Map Overview -->
    <section id="peta-lahan" class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6 scroll-mt-24">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#B88E3E]">Geolokasi & Lahan Binaan</span>
                <h2 class="text-2xl font-black text-slate-900">Peta Sebaran Lahan & Komoditas</h2>
                <p class="text-xs text-slate-500 mt-1">Pemetaan polygon lahan kelompok binaan yang terbuka untuk publik</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $publicLands->count() }} Lahan Terpetakan
                </span>
            </div>
        </div>

        <div id="public-map" class="w-full h-80 sm:h-96 rounded-2xl border border-slate-200 z-10"></div>
    </section>

</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapContainer = document.getElementById('public-map');
        if (!mapContainer) return;

        const map = L.map('public-map').setView([-7.351234, 108.152345], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap | SiYota RUMA'
        }).addTo(map);

        const lands = @json($publicLands);
        const bounds = [];

        lands.forEach(land => {
            if (land.latitude && land.longitude) {
                const marker = L.marker([land.latitude, land.longitude]).addTo(map);
                bounds.push([land.latitude, land.longitude]);

                marker.bindPopup(`
                    <div class="p-1 space-y-1 text-slate-800">
                        <strong class="text-sm font-bold text-[#B88E3E]">${land.name}</strong><br>
                        <span class="text-xs font-medium text-slate-600">${land.group ? land.group.name : ''}</span><br>
                        <span class="text-xs">Luas: <b>${land.area_m2} m²</b></span><br>
                        <span class="text-xs text-emerald-700">Komoditas: ${land.commodities || '-'}</span>
                    </div>
                `);

                // Draw polygon if GeoJSON exists
                if (land.polygon_geojson) {
                    try {
                        const geoData = typeof land.polygon_geojson === 'string' ? JSON.parse(land.polygon_geojson) : land.polygon_geojson;
                        L.geoJSON(geoData, {
                            style: {
                                color: '#1E4D2B',
                                weight: 2,
                                opacity: 0.9,
                                fillColor: '#B88E3E',
                                fillOpacity: 0.25
                            }
                        }).addTo(map);
                    } catch(e) {
                        console.error("Invalid GeoJSON for land", land.id, e);
                    }
                }
            }
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    });
</script>
@endpush
@endsection

