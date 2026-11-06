<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'RUMA — Ruang Usaha Masyarakat Alam') | SiYota.org</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- PWA & Favicon -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#B88E3E">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Tailwind CSS compiled with Vite -->
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #FFFFFF;
        }
        .text-ochre { color: #B88E3E; }
        .bg-ochre { background-color: #B88E3E; }
        .border-ochre { border-color: #B88E3E; }
        .text-forest { color: #1E4D2B; }
        .bg-forest { background-color: #1E4D2B; }
        .border-forest { border-color: #1E4D2B; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="h-full flex flex-col bg-white text-slate-800 antialiased selection:bg-amber-100 selection:text-amber-900">

    <!-- Top Public Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('public.home') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('logo.png') }}" alt="RUMA SiYota" class="w-10 h-10 object-contain rounded-xl group-hover:scale-105 transition-transform">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-lg tracking-tight text-slate-900">RUMA</span>
                                <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded-full bg-[#F8F2E4] text-[#B88E3E] border border-[#E6CE93]/60">SiYota.org</span>
                            </div>
                            <span class="text-[11px] text-slate-500 font-medium -mt-0.5 hidden xs:inline">Ruang Usaha Masyarakat Alam</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Public Nav Links -->
                <nav class="hidden md:flex items-center gap-1 text-xs font-bold">
                    <a href="{{ route('public.home') }}" class="px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('public.home') ? 'text-[#B88E3E] bg-[#FDFBF7]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('public.home') }}#kelompok-binaan" class="px-3.5 py-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                        Kelompok Binaan
                    </a>
                    <a href="{{ route('public.home') }}#peta-lahan" class="px-3.5 py-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                        Peta Lahan
                    </a>
                    <a href="{{ route('register.group') }}" class="px-3.5 py-2 rounded-xl text-[#1E4D2B] hover:bg-emerald-50 transition-colors">
                        + Daftarkan Kelompok
                    </a>
                </nav>

                <!-- Auth / Login / Dashboard Buttons -->
                <div class="flex items-center gap-2.5">
                    @if(Auth::check())
                        @php
                            $user = Auth::user();
                            $cleanRole = $user->clean_role_label;
                            $roleBadgeStyle = match($cleanRole) {
                                'ADMIN' => 'bg-slate-900 text-white',
                                'FASIL' => 'bg-[#1E4D2B] text-white',
                                default => 'bg-[#B88E3E] text-white',
                            };
                        @endphp

                        <!-- Buka Dashboard Sesuai Role -->
                        <a href="{{ $user->home_route }}" class="px-3.5 py-1.5 rounded-full text-xs font-extrabold {{ $roleBadgeStyle }} shadow-xs hover:opacity-90 transition-opacity flex items-center gap-1.5">
                            <span>Buka Dashboard ({{ $cleanRole }})</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>

                        <!-- Dropdown Switch / Logout -->
                        <div class="relative">
                            <button type="button" onclick="document.getElementById('public-user-menu').classList.toggle('hidden')" class="p-1.5 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="public-user-menu" class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-100 py-2 z-50 text-xs">
                                <div class="px-3 py-1.5 border-b border-slate-100">
                                    <div class="font-bold text-slate-900 truncate">{{ $user->name }}</div>
                                    <div class="text-[10px] text-slate-400">Peran: {{ $cleanRole }}</div>
                                </div>
                                <a href="{{ $user->home_route }}" class="block px-3 py-2 text-slate-700 hover:bg-slate-50 font-bold">
                                    Buka Dashboard Saya
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <a href="{{ route('logout') }}" class="block px-3 py-1.5 text-rose-600 hover:bg-rose-50 font-bold">Keluar (Logout)</a>
                            </div>
                        </div>

                    @else
                        <!-- Guest Actions -->
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white text-xs font-extrabold shadow-sm shadow-[#B88E3E]/20 transition-all">
                            Daftar Akun
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-4">
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-4">
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between">
                <span>✕ {{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">✕</button>
            </div>
        </div>
    @endif

    <!-- Main Public Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-8">
        @yield('content')
    </main>

    <!-- Public Footer -->
    <footer class="border-t border-slate-100 bg-white py-8 text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-slate-900">RUMA</span>
                <span>— Ruang Usaha Masyarakat Alam | Inisiatif SiYota.org</span>
            </div>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="{{ route('register.group') }}" class="hover:text-[#B88E3E] font-medium">Daftar Kelompok</a>
                <span>•</span>
                <a href="{{ route('login') }}" class="hover:text-[#B88E3E] font-medium">Masuk Aplikasi</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
