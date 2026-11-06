<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\SystemSetting;
use App\Services\MailNotificationService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $waEnabled = SystemSetting::get('whatsapp_notifications_enabled', config('services.whatsapp.enabled', true) ? '1' : '0');
        $waProvider = SystemSetting::get('whatsapp_provider', config('services.whatsapp.provider', 'fonnte'));
        $waToken = SystemSetting::get('whatsapp_api_token', config('services.whatsapp.api_token', ''));
        $waUrl = SystemSetting::get('whatsapp_api_url', config('services.whatsapp.api_url', 'https://api.fonnte.com/send'));
        $waSender = SystemSetting::get('whatsapp_sender_number', config('services.whatsapp.sender_number', '+6281122334455'));

        $emailEnabled = SystemSetting::get('email_notifications_enabled', '1');
        $emailFromAddress = config('mail.from.address', 'notifications@siyota.org');
        $emailFromName = config('mail.from.name', 'RUMA SiYota');
        $mailMailer = config('mail.default', 'log');

        $googleClientId = config('services.google.client_id');
        $googleRedirectUri = config('services.google.redirect', url('/auth/google/callback'));
        $googleConfigured = ! empty($googleClientId);

        $logs = NotificationLog::with('group')->latest()->paginate(15);

        return view('admin.notifications.index', compact(
            'waEnabled',
            'waProvider',
            'waToken',
            'waUrl',
            'waSender',
            'emailEnabled',
            'emailFromAddress',
            'emailFromName',
            'mailMailer',
            'googleClientId',
            'googleRedirectUri',
            'googleConfigured',
            'logs'
        ));
    }

    public function toggleChannel(Request $request)
    {
        return $this->updateSettings($request);
    }

    public function updateSettings(Request $request)
    {
        SystemSetting::set('whatsapp_notifications_enabled', $request->has('whatsapp_notifications_enabled') ? '1' : '0');
        SystemSetting::set('whatsapp_provider', $request->input('whatsapp_provider', 'fonnte'));
        SystemSetting::set('whatsapp_api_url', $request->input('whatsapp_api_url', 'https://api.fonnte.com/send'));
        SystemSetting::set('whatsapp_sender_number', $request->input('whatsapp_sender_number', '+6281122334455'));

        if ($request->filled('whatsapp_api_token')) {
            SystemSetting::set('whatsapp_api_token', $request->input('whatsapp_api_token'));
        }

        SystemSetting::set('email_notifications_enabled', $request->has('email_notifications_enabled') ? '1' : '0');

        return back()->with('success', 'Konfigurasi gerbang notifikasi berhasil diperbarui.');
    }

    public function testWhatsApp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $message = $request->input('message', 'Halo dari RUMA SiYota! Gerbang WhatsApp notifikasi telah terhubung dengan baik.');
        $result = WhatsAppService::send($request->phone, $message, null, auth()->id(), 'test_gateway');

        if ($result['success']) {
            $info = $result['simulated'] ? ' (Mode Simulasi Dev: Log tercatat)' : ' (Terkirim via Gateway API)';

            return back()->with('success', 'Uji coba kirim WhatsApp berhasil ke '.$result['recipient'].$info);
        }

        return back()->with('error', 'Gagal mengirim WhatsApp: '.($result['error'] ?? 'Terjadi kendala pada gateway.'));
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $result = MailNotificationService::send(
            $request->email,
            'Uji Koneksi Email RUMA SiYota',
            'Halo! Ini adalah email uji coba dari Command Center RUMA (siyota.org). Layanan email transaksional Anda telah siap digunakan.',
            null,
            auth()->id(),
            'test_gateway'
        );

        if ($result['success']) {
            return back()->with('success', 'Uji coba kirim email berhasil ke '.$request->email);
        }

        return back()->with('error', 'Gagal mengirim email: '.($result['error'] ?? 'Periksa konfigurasi SMTP pada .env'));
    }
}
