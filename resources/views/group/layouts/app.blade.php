<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'RUMA Kelompok') | SiYota.org</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- PWA & Mobile Meta -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#B88E3E">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="RUMA">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Vite Tailwind -->
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
        }
        /* Mobile-first pure white app shell */
        .app-shell {
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
            background-color: #FFFFFF;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.04);
            border-left: 1px solid #F1F5F9;
            border-right: 1px solid #F1F5F9;
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
<body class="h-full text-slate-800 antialiased selection:bg-amber-100 selection:text-amber-900">

    <div class="app-shell pb-20">
        <!-- Top App Bar -->
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-100 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="RUMA" class="w-9 h-9 rounded-xl object-contain shadow-xs">
                <div>
                    <h1 class="text-xs font-black uppercase tracking-wider text-slate-900">RUMA Kelompok</h1>
                    <p class="text-[11px] font-semibold text-[#8E6920] truncate max-w-[170px] sm:max-w-xs">
                        {{ Auth::user()->group->name ?? 'Kelompok Tani Binaan' }}
                    </p>
                </div>
            </div>

            <!-- Actions: Logout -->
            <div class="flex items-center gap-2">
                <a href="{{ route('logout') }}" title="Keluar" class="p-1.5 rounded-lg bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
        </header>

        <!-- Flash Notifications -->
        @if(session('success'))
        <div class="mx-4 mt-3 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2 font-medium">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mx-4 mt-3 p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-center gap-2 font-medium">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Main Body -->
        <main class="flex-1 p-4">
            @yield('content')
        </main>

        <!-- In-App PWA Install Banner -->
        <div id="pwaInstallBanner" class="hidden fixed bottom-20 left-4 right-4 max-w-[448px] mx-auto z-50 p-3 bg-slate-900 text-white rounded-2xl shadow-2xl flex items-center justify-between gap-3 border border-slate-800">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('logo.png') }}" alt="RUMA" class="w-8 h-8 rounded-lg object-contain bg-white p-0.5">
                <div>
                    <div class="text-xs font-black">Pasang Aplikasi RUMA</div>
                    <div class="text-[10px] text-slate-300">Buka langsung di layar utama HP</div>
                </div>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <button id="pwaInstallBtn" class="px-3 py-1.5 rounded-xl bg-[#B88E3E] hover:bg-[#9E752D] text-white text-[11px] font-black transition-colors">
                    Pasang
                </button>
                <button id="pwaDismissBtn" class="w-7 h-7 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center text-xs">
                    ✕
                </button>
            </div>
        </div>

        <!-- Bottom Navigation Bar (5 Items) -->
        <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/80 shadow-lg">
            <div class="max-w-[480px] mx-auto px-2 flex items-center justify-around h-16">
                <!-- 1. Beranda -->
                <a href="{{ route('group.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center group transition-colors {{ request()->routeIs('group.dashboard', 'ruma.dashboard') ? 'text-[#B88E3E]' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('group.dashboard', 'ruma.dashboard') ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-[11px] font-bold mt-1">Beranda</span>
                </a>

                <!-- 2. Belajar -->
                <a href="{{ route('group.learning.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center group transition-colors {{ request()->routeIs('group.learning.*', 'ruma.belajar') ? 'text-[#B88E3E]' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('group.learning.*', 'ruma.belajar') ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="text-[11px] font-bold mt-1">Belajar</span>
                </a>

                <!-- 3. Usaha -->
                <a href="{{ route('group.land.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center group transition-colors {{ request()->routeIs('group.land.*', 'group.commodities.*', 'group.finance.*', 'group.products.*', 'ruma.usaha') ? 'text-[#B88E3E]' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('group.land.*', 'group.commodities.*', 'group.finance.*', 'group.products.*', 'ruma.usaha') ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="text-[11px] font-bold mt-1">Usaha</span>
                </a>

                <!-- 4. Marketing -->
                <a href="{{ route('group.marketing.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center group transition-colors {{ request()->routeIs('group.marketing.*', 'ruma.marketing') ? 'text-[#B88E3E]' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('group.marketing.*', 'ruma.marketing') ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    <span class="text-[11px] font-bold mt-1">Marketing</span>
                </a>

                <!-- 5. Akun -->
                <a href="{{ route('group.account.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center group transition-colors {{ request()->routeIs('group.account.*', 'ruma.kelompok') ? 'text-[#B88E3E]' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('group.account.*', 'ruma.kelompok') ? 'text-[#B88E3E]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-[11px] font-bold mt-1">Akun</span>
                </a>
            </div>
        </nav>
    </div>

    @stack('scripts')

    <!-- PWA Service Worker & Install Script -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('RUMA PWA Service Worker Registered', reg))
                    .catch(err => console.log('SW Registration error:', err));
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        const installBanner = document.getElementById('pwaInstallBanner');
        const installBtn = document.getElementById('pwaInstallBtn');
        const dismissBtn = document.getElementById('pwaDismissBtn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            // Check if user dismissed recently
            if (!localStorage.getItem('ruma_pwa_dismissed')) {
                installBanner.classList.remove('hidden');
                installBanner.classList.add('flex');
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                installBanner.classList.add('hidden');
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
            });
        }

        if (dismissBtn) {
            dismissBtn.addEventListener('click', () => {
                installBanner.classList.add('hidden');
                localStorage.setItem('ruma_pwa_dismissed', '1');
            });
        }
    </script>
</body>
</html>

