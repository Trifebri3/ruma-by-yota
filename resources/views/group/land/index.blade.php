@extends('group.layouts.app')

@section('title', 'Kelola Lahan Kelompok — RUMA')

@section('content')
<div class="space-y-4">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#B88E3E]">Sumber Daya Usaha</span>
            <h2 class="text-lg font-black text-slate-900">Pemetaan Lahan Kelompok</h2>
        </div>
        <button onclick="document.getElementById('modal-add-land').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-extrabold text-xs shadow-xs flex items-center gap-1.5 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Catat Lahan</span>
        </button>
    </div>

    <!-- Sub Navigation for Usaha (Using Clean SVG Icons) -->
    <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl overflow-x-auto hide-scrollbar text-xs font-bold">
        <a href="{{ route('group.land.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl bg-white text-slate-900 shadow-xs whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-[#B88E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            <span>Lahan</span>
        </a>
        <a href="{{ route('group.commodities.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            <span>Komoditas</span>
        </a>
        <a href="{{ route('group.finance.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Keuangan</span>
        </a>
        <a href="{{ route('group.products.index') }}" class="flex-1 py-1.5 px-2.5 text-center rounded-xl text-slate-500 hover:text-slate-800 whitespace-nowrap flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span>Produk & QC</span>
        </a>
    </div>

    <!-- Summary Total Area Card -->
    <div class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-200/80 rounded-2xl p-4 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800">Total Luas Lahan Terdata</span>
            <div class="text-xl font-black text-slate-900 mt-0.5">
                {{ number_format($totalAreaM2, 0, ',', '.') }} <span class="text-xs text-slate-500 font-semibold">m²</span>
            </div>
            <p class="text-[11px] text-emerald-700 font-medium">Setara ~{{ $totalAreaHa }} Hektar ({{ $lands->count() }} Hamparan Lahan)</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <!-- Leaflet Map Container -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-2 shadow-xs space-y-2">
        <div class="flex items-center justify-between px-2 pt-1 text-xs">
            <span class="font-extrabold text-slate-800">Visualisasi Polygon Lahan</span>
            <span class="text-[10px] text-slate-400">Sentuh pin untuk detail</span>
        </div>
        <div id="land-map" class="w-full h-56 rounded-xl border border-slate-100 z-10"></div>
    </div>

    <!-- Lands List -->
    <div class="space-y-2.5">
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-700">Daftar Titik Lahan</h3>
        
        @forelse($lands as $land)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs space-y-2">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <div class="flex items-center gap-1.5">
                        <h4 class="text-xs font-extrabold text-slate-900">{{ $land->name }}</h4>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-600">
                            {{ $land->land_status }}
                        </span>
                    </div>
                    <p class="text-[11px] text-[#1E4D2B] font-semibold mt-1">Komoditas: {{ $land->commodities ?? '-' }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-black text-[#B88E3E]">{{ number_format($land->area_m2, 0, ',', '.') }} m²</span>
                    <span class="block text-[10px] text-slate-400 font-medium">PIC: {{ $land->manager_name ?? 'Kelompok' }}</span>
                </div>
            </div>

            @if($land->notes)
            <p class="text-[11px] text-slate-500 bg-slate-50 p-2 rounded-xl">{{ $land->notes }}</p>
            @endif
        </div>
        @empty
        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-500">
            Belum ada data lahan yang didaftarkan.
        </div>
        @endforelse
    </div>

    <!-- Modal Add Land (MUDAH: TANPA RAW JSON TEXTAREA!) -->
    <div id="modal-add-land" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full max-w-lg rounded-t-3xl sm:rounded-3xl p-5 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#B88E3E] flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-900">Tambah Data Lahan Baru</h3>
                </div>
                <button type="button" onclick="document.getElementById('modal-add-land').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('group.land.store') }}" method="POST" onsubmit="generatePolygonBeforeSubmit()" class="space-y-3.5 text-xs">
                @csrf
                
                <!-- Hidden polygon geojson (Otomatis dibuat oleh sistem di background) -->
                <input type="hidden" name="polygon_geojson" id="input-polygon-geojson">

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lahan / Blok Kebun *</label>
                    <input type="text" name="name" required placeholder="Contoh: Blok Kebun Pasir Honje" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Kepemilikan *</label>
                        <select name="land_status" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
                            <option value="Milik Kelompok">Milik Kelompok</option>
                            <option value="Sewa">Sewa</option>
                            <option value="Kas Desa">Kas Desa</option>
                            <option value="Milik Anggota">Milik Anggota</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Luas Lahan (m²) *</label>
                        <input type="number" id="input-area-m2" name="area_m2" required placeholder="1500" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pengelola / PIC</label>
                        <input type="text" name="manager_name" placeholder="Nama pengelola" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Komoditas Utama</label>
                        <input type="text" name="commodities" placeholder="Cabai, Aren, Sayuran" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
                    </div>
                </div>

                <!-- Bagian Koordinat & GPS: Sangat Mudah untuk Petani -->
                <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="font-extrabold text-slate-900 text-[11px] flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            Titik Lokasi & Pemetaan Peta
                        </span>
                        <span id="gps-status" class="text-[10px] text-emerald-700 font-bold"></span>
                    </div>

                    <button type="button" onclick="getCurrentLocation()" class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-xs transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18l9-5-9-5-9 5 9 5z"></path></svg>
                        <span>Ambil Titik GPS Saya di Kebun Ini</span>
                    </button>

                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div>
                            <label class="block font-semibold text-slate-500 mb-0.5">Latitude</label>
                            <input type="text" id="input-lat" name="latitude" value="-7.351234" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-500 mb-0.5">Longitude</label>
                            <input type="text" id="input-lng" name="longitude" value="108.152345" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white font-mono">
                        </div>
                    </div>

                    <p class="text-[10px] text-slate-500 leading-snug flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#B88E3E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span><b>Otomatis:</b> Sistem RUMA akan otomatis menggambar polygon hamparan di peta berdasarkan titik koordinat dan luas m² yang Anda isi di atas.</span>
                    </p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Akses jalan, kondisi mata air, batas sungai..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-land').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#B88E3E] text-white font-extrabold hover:bg-[#9E752D] shadow-xs">
                        Simpan Lahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapContainer = document.getElementById('land-map');
        if (!mapContainer) return;

        const map = L.map('land-map').setView([-7.351234, 108.152345], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const lands = @json($lands);
        const bounds = [];

        lands.forEach(land => {
            if (land.latitude && land.longitude) {
                const marker = L.marker([land.latitude, land.longitude]).addTo(map);
                bounds.push([land.latitude, land.longitude]);

                marker.bindPopup(`
                    <div class="text-xs">
                        <strong class="text-[#B88E3E]">${land.name}</strong><br>
                        Luas: ${land.area_m2} m²<br>
                        Tanaman: ${land.commodities || '-'}
                    </div>
                `);

                if (land.polygon_geojson) {
                    try {
                        const geoData = typeof land.polygon_geojson === 'string' ? JSON.parse(land.polygon_geojson) : land.polygon_geojson;
                        L.geoJSON(geoData, {
                            style: { color: '#1E4D2B', weight: 2, fillColor: '#B88E3E', fillOpacity: 0.3 }
                        }).addTo(map);
                    } catch(e) {}
                }
            }
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [20, 20] });
        }
    });

    function getCurrentLocation() {
        const status = document.getElementById('gps-status');
        if (navigator.geolocation) {
            status.textContent = "Mencari satelit GPS...";
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    document.getElementById('input-lat').value = lat;
                    document.getElementById('input-lng').value = lng;
                    status.textContent = "✓ Titik GPS Terkunci!";
                },
                function(error) {
                    status.textContent = "✕ GPS Gagal (" + error.message + ")";
                },
                { enableHighAccuracy: true }
            );
        } else {
            status.textContent = "Perangkat tidak mendukung GPS";
        }
    }

    function generatePolygonBeforeSubmit() {
        const lat = parseFloat(document.getElementById('input-lat').value) || -7.351234;
        const lng = parseFloat(document.getElementById('input-lng').value) || 108.152345;
        const area = parseFloat(document.getElementById('input-area-m2').value) || 1500;

        // Hitung estimasi offset polygon persegi di sekitar titik koordinat berdasarkan luas m²
        const delta = Math.sqrt(area) / 111320; // konversi meter ke derajat lintang/bujur

        const polygon = {
            "type": "Polygon",
            "coordinates": [[
                [lng - delta/2, lat - delta/2],
                [lng + delta/2, lat - delta/2],
                [lng + delta/2, lat + delta/2],
                [lng - delta/2, lat + delta/2],
                [lng - delta/2, lat - delta/2]
            ]]
        };

        document.getElementById('input-polygon-geojson').value = JSON.stringify(polygon);
    }
</script>
@endpush
@endsection
