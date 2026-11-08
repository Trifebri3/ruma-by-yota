<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MailNotificationService
{
    /**
     * Kirim Notifikasi Email Transaksional
     */
    public static function send(string $email, string $subject, string $content, ?int $groupId = null, ?int $userId = null, string $eventType = 'general'): array
    {
        $enabled = SystemSetting::get('email_notifications_enabled', '1');

        if ($enabled === '0' || $enabled === false) {
            return [
                'success' => false,
                'message' => 'Notifikasi Email sedang dinonaktifkan di pengaturan sistem.',
            ];
        }

        $status = 'sent';
        $errorMessage = null;

        try {
            Mail::raw($content, function ($message) use ($email, $subject) {
                $fromAddress = config('mail.from.address', 'notifications@siyota.org');
                $fromName = config('mail.from.name', 'RUMA SiYota');

                $message->to($email)
                    ->from($fromAddress, $fromName)
                    ->subject($subject);
            });
        } catch (\Throwable $e) {
            $status = 'failed';
            $errorMessage = $e->getMessage();
            Log::error("[Email Send Error] {$email}: {$errorMessage}");
        }

        NotificationLog::create([
            'group_id' => $groupId,
            'user_id' => $userId,
            'channel' => 'email',
            'event_type' => $eventType,
            'recipient' => $email,
            'message_preview' => Str::limit("[{$subject}] {$content}", 250),
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => now(),
        ]);

        return [
            'success' => $status === 'sent',
            'status' => $status,
            'recipient' => $email,
            'error' => $errorMessage,
        ];
    }
}
