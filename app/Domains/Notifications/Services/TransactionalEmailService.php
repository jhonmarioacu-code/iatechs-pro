<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Services;

use Illuminate\Support\Facades\Mail;
use App\Domains\Notifications\Models\Notification;
use App\Domains\Notifications\Mail\TransactionalNotificationMail;
use App\Domains\Shared\Exceptions\DomainOperationException;
use App\Domains\Users\Models\User;

class TransactionalEmailService
{
    public function sendNotification(Notification $notification): array
    {
        $recipient = $this->resolveRecipient($notification);
        if ($recipient === null) {
            throw new DomainOperationException('No existe destinatario valido para notificacion por correo.');
        }

        $provider = strtoupper((string) config('services.transactional_email.provider', 'GENERIC'));
        $mailer = (string) config('services.transactional_email.mailer', config('mail.default'));
        if ($mailer === '') {
            $mailer = (string) config('mail.default');
        }

        Mail::mailer($mailer)
            ->to($recipient)
            ->send(new TransactionalNotificationMail($notification));

        return [
            'provider' => $provider,
            'mailer' => $mailer,
            'recipient' => $recipient,
            'delivered_at' => now()->toIso8601String(),
        ];
    }

    private function resolveRecipient(Notification $notification): ?string
    {
        $recipient = trim((string) ($notification->recipient ?? ''));
        if ($recipient !== '' && filter_var($recipient, FILTER_VALIDATE_EMAIL) !== false) {
            return mb_strtolower($recipient);
        }

        $userEmail = '';
        if ($notification->user_id !== null) {
            $user = User::query()->find($notification->user_id);
            if ($user !== null) {
                $userEmail = trim((string) $user->email);
            }
        }
        if ($userEmail !== '' && filter_var($userEmail, FILTER_VALIDATE_EMAIL) !== false) {
            return mb_strtolower($userEmail);
        }

        return null;
    }
}
