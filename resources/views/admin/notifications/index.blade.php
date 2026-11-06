@extends('admin.layouts.app')

@section('title', 'Konfigurasi WhatsApp, Email & Google — Super Admin')

@section('content')
<div class="space-y-6 pb-16">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pusat Komunikasi & Integrasi</span>
            <h1 class="text-2xl font-black text-slate-900">Konfigurasi WhatsApp, Email & Google</h1>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">&larr; Dashboard</a>
    </div>

    <!-- 3 KARTU INTEGRASI (WHATSAPP, EMAIL, GOOGLE) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- 1. WHATSAPP GATEWAY -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-5 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">WhatsApp Gateway</h3>
                            <span class="text-[10px] text-slate-400 font-medium">Fonnte / Wablas / Meta</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $waEnabled == '1' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                        {{ $waEnabled == '1' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <form action="{{ route('admin.notifications.toggle') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <input type="checkbox" name="whatsapp_notifications_enabled" value="1" {{ $waEnabled == '1' ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span class="font-bold text-slate-700">Aktifkan Notifikasi WhatsApp Otomatis</span>
                    </label>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provider WhatsApp:</label>
                        <select name="whatsapp_provider" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="fonnte" {{ $waProvider === 'fonnte' ? 'selected' : '' }}>Fonnte (Rekomendasi Indonesia)</option>
                            <option value="wablas" {{ $waProvider === 'wablas' ? 'selected' : '' }}>Wablas</option>
                            <option value="meta" {{ $waProvider === 'meta' ? 'selected' : '' }}>WhatsApp Cloud API (Meta Official)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor Pengirim (Sender):</label>
                        <input type="text" name="whatsapp_sender_number" value="{{ $waSender }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">API Token / Secret Key:</label>
                        <input type="password" name="whatsapp_api_token" value="{{ $waToken }}" placeholder="Masukkan API Token..." class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono">
                        <p class="text-[10px] text-slate-400 mt-1">Kosongkan untuk tetap menggunakan mode simulasi log.</p>
                    </div>

                    <button type="submit" class="w-full py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition-colors">
                        Simpan WhatsApp Gateway
                    </button>
                </form>
            </div>

            <!-- Uji Coba WhatsApp -->
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <span class="block text-[11px] font-black uppercase text-slate-400">Tes Kirim Pesan:</span>
                <form action="{{ route('admin.notifications.test_wa') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="phone" required placeholder="08123456789" class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-mono">
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 shrink-0">
                        Uji Kirim
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. EMAIL GATEWAY (SMTP / GMAIL / RESEND) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-5 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-800 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Email Gateway</h3>
                            <span class="text-[10px] text-slate-400 font-medium">SMTP / Gmail / Resend</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $emailEnabled == '1' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-500' }}">
                        {{ $emailEnabled == '1' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Mailer Driver:</span>
                        <strong class="font-mono text-slate-800">{{ strtoupper($mailMailer) }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">From Address:</span>
                        <strong class="font-mono text-slate-800">{{ $emailFromAddress }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">From Name:</span>
                        <strong class="text-slate-800">{{ $emailFromName }}</strong>
                    </div>
                </div>

                <form action="{{ route('admin.notifications.toggle') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <input type="checkbox" name="email_notifications_enabled" value="1" {{ $emailEnabled == '1' ? 'checked' : '' }} class="rounded text-sky-600 focus:ring-sky-500">
                        <span class="font-bold text-slate-700">Aktifkan Notifikasi Email Transaksional</span>
                    </label>

                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Pengaturan detail host SMTP, port, username & password Gmail/Resend diatur aman langsung melalui file <code class="font-bold text-slate-700">.env</code> server.
                    </p>

                    <button type="submit" class="w-full py-2 rounded-xl bg-sky-700 hover:bg-sky-800 text-white font-bold text-xs shadow-xs transition-colors">
                        Perbarui Status Email
                    </button>
                </form>
            </div>

            <!-- Uji Coba Email -->
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <span class="block text-[11px] font-black uppercase text-slate-400">Tes Kirim Email:</span>
                <form action="{{ route('admin.notifications.test_email') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="email" name="email" required placeholder="admin@siyota.org" class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-mono">
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 shrink-0">
                        Uji Kirim
                    </button>
                </form>
            </div>
        </div>

        <!-- 3. GOOGLE SIGN-IN / OAUTH -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-5 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center">
                            <!-- Google Logo -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Google OAuth 2.0</h3>
                            <span class="text-[10px] text-slate-400 font-medium">Sign in with Google</span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $googleConfigured ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $googleConfigured ? 'Production' : 'Dev Simulation' }}
                    </span>
                </div>

                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-[11px] font-bold text-slate-600 block mb-0.5">Authorized Redirect URI (Google Console):</span>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-200 font-mono text-[11px] text-slate-800 break-all select-all flex items-center justify-between">
                            <span>{{ $googleRedirectUri }}</span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $googleRedirectUri }}'); alert('Redirect URI disalin!');" class="text-[#B88E3E] font-bold shrink-0 ml-2">Salin</button>
                        </div>
                    </div>

                    <div class="p-3 bg-amber-50/70 rounded-2xl border border-amber-200/70 text-[11px] text-amber-900 space-y-1">
                        <strong>Panduan Pengaturan Google Cloud:</strong>
                        <p class="leading-relaxed">1. Buka <i>Google Cloud Console &rarr; APIs & Services &rarr; Credentials</i>.<br>2. Buat <i>OAuth Client ID (Web application)</i>.<br>3. Masukkan Redirect URI di atas.<br>4. Masukkan Client ID & Secret ke file <code class="font-bold">.env</code>.</p>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 text-right">
                <a href="{{ route('auth.google') }}" target="_blank" class="w-full inline-block py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs text-center transition-colors">
                    Uji Coba Alur Google Login &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- NOTIFICATION LOGS TABLE -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Riwayat Log Pesan Terkirim</h3>
                <p class="text-xs text-slate-400">Monitoring pengiriman WhatsApp dan Email otomatis dari sistem</p>
            </div>
            <span class="text-xs text-slate-500 font-semibold">Total {{ $logs->total() }} Pesan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 font-extrabold uppercase text-[10px]">
                        <th class="py-2.5 px-3">Saluran</th>
                        <th class="py-2.5 px-3">Penerima</th>
                        <th class="py-2.5 px-3">Jenis Kejadian</th>
                        <th class="py-2.5 px-3">Isi Pesan</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $lg)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-3 font-bold text-slate-800">
                            @if($lg->channel === 'whatsapp')
                                <span class="flex items-center gap-1.5 text-emerald-700 font-bold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    WhatsApp
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-sky-700 font-bold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Email
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-3">
                            <span class="font-extrabold text-slate-900 block">{{ $lg->group->name ?? 'User' }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $lg->recipient }}</span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                {{ $lg->event_type }}
                            </span>
                        </td>
                        <td class="py-3 px-3 max-w-xs truncate text-slate-600">
                            {{ $lg->message_preview }}
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold {{ $lg->status === 'sent' ? 'bg-emerald-100 text-emerald-800' : ($lg->status === 'queued' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ strtoupper($lg->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-[11px] text-slate-400 whitespace-nowrap">
                            {{ $lg->created_at->format('d M H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400">Belum ada riwayat notifikasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
