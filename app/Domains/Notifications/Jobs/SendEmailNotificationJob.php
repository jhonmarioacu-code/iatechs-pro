<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Jobs;

use App\Domains\Notifications\Models\Notification;
use App\Domains\Notifications\Services\NotificationService;
use App\Domains\Notifications\Services\TransactionalEmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private readonly int $notificationId
    ) {
        $this->onQueue('notifications');
    }

    public function handle(NotificationService $notificationService): void
    {
        $notification = Notification::query()->find($this->notificationId);

        if (!$notification) {
            return;
        }

        $notificationService->update($notification, [
            'status' => 'PROCESSING',
        ]);

        $result = app(TransactionalEmailService::class)
            ->sendNotification($notification->fresh(['user']));

        $notificationService->markAsDelivered(
            $notification->fresh(),
            [
                'email_delivery' => $result,
            ]
        );
    }

    public function failed(\Throwable $exception): void
    {
        $notification = Notification::query()->find($this->notificationId);

        if (!$notification) {
            return;
        }

        app(NotificationService::class)->markAsFailed($notification, $exception->getMessage());
    }
}
