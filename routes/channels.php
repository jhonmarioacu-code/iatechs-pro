<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;
use App\Domains\Users\Models\User;

Broadcast::channel('company.{companyId}.notifications', function (User $user, int $companyId): bool {
    return
        (int) $user->company_id === $companyId
        && $user->can('notifications.view');
});

Broadcast::channel('user.{userId}.notifications', function (User $user, int $userId): bool {
    return
        (int) $user->id === $userId
        && $user->can('notifications.view');
});
