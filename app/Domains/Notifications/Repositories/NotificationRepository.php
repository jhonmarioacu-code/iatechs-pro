<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Notifications\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Notification::query()
            ->with([
                'company',
                'user'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Notification {

        return Notification::create(
            $data
        );
    }

    public function update(
        Notification $notification,
        array $data
    ): Notification {

        $notification->update($data);

        return $notification->refresh();
    }

    public function find(
        int $id
    ): ?Notification {

        return Notification::find($id);
    }
}