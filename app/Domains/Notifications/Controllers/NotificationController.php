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
        return NotificationResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreNotificationRequest $request
    )
    {
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
        return new NotificationResource(
            $notification
        );
    }

    public function update(
        UpdateNotificationRequest $request,
        Notification $notification
    )
    {
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
        return new NotificationResource(
            $this->service->markAsRead(
                $notification
            )
        );
    }
}
