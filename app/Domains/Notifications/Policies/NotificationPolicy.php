<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Notifications\Models\Notification;

class NotificationPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'notifications.view'
        );
    }

    public function view(
        User $user,
        Notification $notification
    ): bool {

        return $user->company_id ===
            $notification->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'notifications.create'
        );
    }

    public function update(
        User $user
    ): bool {

        return $user->can(
            'notifications.update'
        );
    }

    public function delete(
        User $user
    ): bool {

        return $user->can(
            'notifications.delete'
        );
    }

    public function markAsRead(
        User $user
    ): bool {

        return $user->can(
            'notifications.read'
        );
    }
}