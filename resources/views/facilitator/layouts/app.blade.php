<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Fasilitator SiYota') | RUMA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; background-color: #FFFFFF; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="h-full flex flex-col bg-white text-slate-800 antialiased">

    <!-- Top Facilitator Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-emerald-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand & Fasil Badge -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('facilitator.dashboard') }}" class="flex items-center gap-2.5">
                        <img src="{{ asset('logo.png') }}" alt="RUMA Fasilitator" class="w-10 h-10 object-contain rounded-xl shadow-xs">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-lg tracking-tight text-slate-900">RUMA</span>
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-[#1E4D2B] text-white">
                                    FASILITATOR
                                </span>
                            </div>
                            <span class="text-[11px] text-[#1E4D2B] font-semibold">Pendampingan Lapangan SiYota</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Fasil Nav -->
                <nav class="hidden lg:flex items-center gap-1 text-xs font-bold">
                    <a href="{{ route('facilitator.dashboard') }}" class="px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('facilitator.dashboard') ? 'bg-[#1E4D2B] text-white' : 'text-slate-600 hover:text-[#1E4D2B] hover:bg-emerald-50' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('facilitator.groups.index') }}" class="px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('facilitator.groups.*') ? 'bg-[#1E4D2B] text-white' : 'text-slate-600 hover:text-[#1E4D2B] hover:bg-emerald-50' }}">
                        Kelompok Binaan
                    </a>
                    <a href="{{ route('facilitator.tasks.index') }}" class="px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('facilitator.tasks.*') ? 'bg-[#1E4D2B] text-white' : 'text-slate-600 hover:text-[#1E4D2B] hover:bg-emerald-50' }}">
                        Tugas Pemasaran
                    </a>
                    <a href="{{ route('facilitator.reviews.index') }}" class="px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('facilitator.reviews.*') ? 'bg-[#1E4D2B] text-white' : 'text-slate-600 hover:text-[#1E4D2B] hover:bg-emerald-50' }}">
                        Review Kiriman & QC
                    </a>
                    <a href="{{ route('facilitator.mentoring.index') }}" class="px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('facilitator.mentoring.*') ? 'bg-[#1E4D2B] text-white' : 'text-slate-600 hover:text-[#1E4D2B] hover:bg-emerald-50' }}">
                        Mentoring Lapangan
                    </a>
                </nav>

                <!-- Actions: Web Publik & Logout -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('public.home') }}" target="_blank" class="px-3 py-1.5 rounded-full border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-xs font-bold text-[#1E4D2B] flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        <span>Lihat Web</span>
                    </a>

                    <!-- Logout -->
                    <a href="{{ route('logout') }}" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Keluar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-6 pb-24 lg:pb-12">
        <!-- Flash notifications -->
        @if(session('success'))
            <div class="mb-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between">
                <span>✕ {{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">✕</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Mobile Nav FASILITATOR (Bottom Bar) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-emerald-100 px-2 py-1.5 shadow-lg">
        <div class="max-w-md mx-auto grid grid-cols-5 items-center gap-1 text-center">
            <a href="{{ route('facilitator.dashboard') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('facilitator.dashboard') ? 'text-[#1E4D2B] font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="text-[10px] mt-0.5">Beranda</span>
            </a>
            <a href="{{ route('facilitator.groups.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('facilitator.groups.*') ? 'text-[#1E4D2B] font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="text-[10px] mt-0.5">Kelompok</span>
            </a>
            <a href="{{ route('facilitator.tasks.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('facilitator.tasks.*') ? 'text-[#1E4D2B] font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-[10px] mt-0.5">Tugas</span>
            </a>
            <a href="{{ route('facilitator.reviews.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('facilitator.reviews.*') ? 'text-[#1E4D2B] font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span class="text-[10px] mt-0.5">Review QC</span>
            </a>
            <a href="{{ route('facilitator.mentoring.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('facilitator.mentoring.*') ? 'text-[#1E4D2B] font-bold' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="text-[10px] mt-0.5">Mentoring</span>
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>

