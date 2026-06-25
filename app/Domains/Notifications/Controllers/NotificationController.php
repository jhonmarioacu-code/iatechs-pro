<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Notifications\Models\Notification;
use App\Domains\Notifications\Services\NotificationService;
use App\Domains\Notifications\Requests\StoreNotificationRequest;
use App\Domains\Notifications\Requests\UpdateNotificationRequest;
use App\Domains\Notifications\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Notification::class);

        return NotificationResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreNotificationRequest $request
    )
    {
        $this->authorize('create', Notification::class);

        return new NotificationResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        Notification $notification
    )
    {
        $this->authorize('view', $notification);

        return new NotificationResource(
            $notification
        );
    }

    public function update(
        UpdateNotificationRequest $request,
        Notification $notification
    )
    {
        $this->authorize('update', $notification);

        return new NotificationResource(
            $this->service->update(
                $notification,
                $request->validated()
            )
        );
    }

    public function markAsRead(
        Notification $notification
    )
    {
        $this->authorize('markAsRead', $notification);

        return new NotificationResource(
            $this->service->markAsRead(
                $notification
            )
        );
    }
}
