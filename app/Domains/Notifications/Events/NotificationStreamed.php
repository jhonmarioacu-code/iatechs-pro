<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use App\Domains\Notifications\Models\Notification;

class NotificationStreamed implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public string $broadcastQueue = 'default';

    public function __construct(
        public Notification $notification,
        public string $action = 'updated'
    ) {}

    public function broadcastAs(): string
    {
        return 'notifications.streamed';
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel(
                'company.'.$this->notification->company_id.'.notifications'
            ),
        ];

        if ($this->notification->user_id !== null) {
            $channels[] = new PrivateChannel(
                'user.'.$this->notification->user_id.'.notifications'
            );
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
            'notification' => [
                'id' => $this->notification->id,
                'uuid' => $this->notification->uuid,
                'company_id' => $this->notification->company_id,
                'user_id' => $this->notification->user_id,
                'title' => $this->notification->title,
                'message' => $this->notification->message,
                'type' => $this->notification->type,
                'channel' => $this->notification->channel,
                'status' => $this->notification->status,
                'data' => $this->notification->data,
                'sent_at' => optional($this->notification->sent_at)?->toISOString(),
                'delivered_at' => optional($this->notification->delivered_at)?->toISOString(),
                'read_at' => optional($this->notification->read_at)?->toISOString(),
                'created_at' => optional($this->notification->created_at)?->toISOString(),
                'updated_at' => optional($this->notification->updated_at)?->toISOString(),
            ],
        ];
    }
}

