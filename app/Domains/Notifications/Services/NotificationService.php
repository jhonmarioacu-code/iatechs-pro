<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Services;

use Illuminate\Support\Str;

use App\Domains\Notifications\Jobs\SendEmailNotificationJob;
use App\Domains\Notifications\Jobs\SendSmsNotificationJob;
use App\Domains\Notifications\Jobs\SendWhatsappNotificationJob;
use App\Domains\Notifications\Events\NotificationStreamed;
use App\Domains\Notifications\Models\Notification;
use App\Domains\Notifications\Repositories\NotificationRepository;

class NotificationService
{
    public function __construct(
        private NotificationRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    ) {
        return $this->repository
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Notification {

        $notification = $this->repository->create([

            'uuid' => (string) Str::uuid(),

            'company_id' => $data['company_id'],

            'user_id' => $data['user_id'] ?? null,

            'title' => $data['title'],

            'message' => $data['message'],

            'type' => $data['type'],

            'channel' => $data['channel'],

            'status' => 'PENDING',

            'recipient' =>
                $data['recipient'] ?? null,

            'subject' =>
                $data['subject'] ?? null,

            'data' =>
                $data['data'] ?? null,
        ]);

        $this->dispatchByChannel($notification);
        NotificationStreamed::dispatch($notification->fresh(), 'created');

        return $notification;
    }

    public function markAsSent(
        Notification $notification
    ): Notification {

        $updated = $this->repository->update(
            $notification,
            [
                'status' => 'SENT',
                'sent_at' => now()
            ]
        );

        NotificationStreamed::dispatch($updated, 'sent');

        return $updated;
    }

    public function markAsRead(
        Notification $notification
    ): Notification {

        $updated = $this->repository->update(
            $notification,
            [
                'status' => 'READ',
                'read_at' => now()
            ]
        );

        NotificationStreamed::dispatch($updated, 'read');

        return $updated;
    }

    public function update(
        Notification $notification,
        array $data
    ): Notification {
        $updated = $this->repository
            ->update(
                $notification,
                $data
            );

        NotificationStreamed::dispatch($updated, 'updated');

        return $updated;
    }

    public function markAsFailed(
        Notification $notification,
        string $error
    ): Notification {

        $updated = $this->repository->update(
            $notification,
            [
                'status' => 'FAILED',
                'error_message' => $error
            ]
        );

        NotificationStreamed::dispatch($updated, 'failed');

        return $updated;
    }

    private function dispatchByChannel(
        Notification $notification
    ): void {
        match ($notification->channel) {
            'EMAIL' => SendEmailNotificationJob::dispatch($notification->id),
            'SMS' => SendSmsNotificationJob::dispatch($notification->id),
            'WHATSAPP' => SendWhatsappNotificationJob::dispatch($notification->id),
            default => null,
        };
    }
}
