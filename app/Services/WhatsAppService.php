<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsAppService
{
    /**
     * Kirim Pesan WhatsApp Gateway
     */
    public static function send(string $phone, string $message, ?int $groupId = null, ?int $userId = null, string $eventType = 'general'): array
    {
        $enabled = SystemSetting::get('whatsapp_notifications_enabled', config('services.whatsapp.enabled', true));

        $target = static::normalizePhone($phone);
        $provider = SystemSetting::get('whatsapp_provider', config('services.whatsapp.provider', 'fonnte'));
        $token = SystemSetting::get('whatsapp_api_token', config('services.whatsapp.api_token'));
        $apiUrl = SystemSetting::get('whatsapp_api_url', config('services.whatsapp.api_url', 'https://api.fonnte.com/send'));

        if (! $enabled) {
            return [
                'success' => false,
                'message' => 'Notifikasi WhatsApp sedang dinonaktifkan di pengaturan sistem.',
            ];
        }

        $status = 'sent';
        $errorMessage = null;

        // Jika API Token tersedia, kirim ke gateway riil
        if (! empty($token)) {
            try {
                if ($provider === 'fonnte') {
                    $response = Http::timeout(10)
                        ->withHeaders(['Authorization' => $token])
                        ->post($apiUrl ?: 'https://api.fonnte.com/send', [
                            'target' => $target,
                            'message' => $message,
                        ]);
                } elseif ($provider === 'wablas') {
                    $response = Http::timeout(10)
                        ->withHeaders(['Authorization' => $token])
                        ->post($apiUrl ?: 'https://phone.wablas.com/api/send-message', [
                            'phone' => $target,
                            'message' => $message,
                        ]);
                } else {
                    $response = Http::timeout(10)
                        ->withToken($token)
                        ->post($apiUrl, [
                            'to' => $target,
                            'text' => $message,
                        ]);
                }

                if (! $response->successful()) {
                    $status = 'failed';
                    $errorMessage = 'Gateway HTTP Error: '.$response->status().' '.$response->body();
                }
            } catch (\Throwable $e) {
                $status = 'failed';
                $errorMessage = $e->getMessage();
            }
        } else {
            // Mode Simulasi / Local Dev Logging
            Log::info("[WhatsApp Simulated Send] Ke: {$target} | Pesan: {$message}");
        }

        // Simpan catatan riwayat ke NotificationLog
        NotificationLog::create([
            'group_id' => $groupId,
            'user_id' => $userId,
            'channel' => 'whatsapp',
            'event_type' => $eventType,
            'recipient' => $target,
            'message_preview' => Str::limit($message, 250),
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => now(),
        ]);

        return [
            'success' => $status === 'sent',
            'status' => $status,
            'recipient' => $target,
            'simulated' => empty($token),
            'error' => $errorMessage,
        ];
    }

    /**
     * Standarisasi format nomor HP Indonesia (contoh: 08123... -> 628123...)
     */
    public static function normalizePhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62'.$clean;
        }

        return $clean;
    }
}
