<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Notifications\Models\Notification;
use App\Domains\Notifications\Services\NotificationService;
use App\Domains\Notifications\Events\NotificationStreamed;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('dispatches realtime notification events on create and read actions', function (): void {
    $company = sec_create_company('Realtime Event Co', 'realtime-event-co');
    $user = sec_create_user(
        $company,
        'realtime-event-user@example.com',
        'owner',
        ['notifications.view', 'notifications.create', 'notifications.read']
    );

    Event::fake([NotificationStreamed::class]);
    Queue::fake();

    /** @var NotificationService $service */
    $service = app(NotificationService::class);

    $notification = $service->create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'Realtime test',
        'message' => 'Notification payload for realtime test.',
        'type' => 'INFO',
        'channel' => 'IN_APP',
        'data' => [
            'source' => 'tests',
        ],
    ]);

    Event::assertDispatched(NotificationStreamed::class, function (NotificationStreamed $event) use ($notification): bool {
        return $event->action === 'created'
            && $event->notification->id === $notification->id;
    });

    $service->markAsRead($notification);

    Event::assertDispatched(NotificationStreamed::class, function (NotificationStreamed $event) use ($notification): bool {
        return $event->action === 'read'
            && $event->notification->id === $notification->id
            && $event->notification->status === 'READ';
    });

    $current = Notification::query()->findOrFail($notification->id);
    expect($current->status)->toBe('READ');
});

