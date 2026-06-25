<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Subscriptions\Models\Subscription;

class SubscriptionPolicy
{
    /**
     * View Any
     */
    public function viewAny(
        User $user
    ): bool {
        return $user->can(
            'subscriptions.view'
        );
    }

    /**
     * View
     */
    public function view(
        User $user,
        Subscription $subscription
    ): bool {
        return $user->can(
            'subscriptions.view'
        );
    }

    /**
     * Create
     */
    public function create(
        User $user
    ): bool {
        return $user->can(
            'subscriptions.create'
        );
    }

    /**
     * Update
     */
    public function update(
        User $user,
        Subscription $subscription
    ): bool {
        return $user->can(
            'subscriptions.update'
        );
    }

    /**
     * Delete
     */
    public function delete(
        User $user,
        Subscription $subscription
    ): bool {
        return $user->can(
            'subscriptions.delete'
        );
    }

    /**
     * Activate
     */
    public function activate(
        User $user,
        Subscription $subscription
    ): bool {
        return $user->can(
            'subscriptions.activate'
        );
    }

    /**
     * Cancel
     */
    public function cancel(
        User $user,
        Subscription $subscription
    ): bool {
        return $user->can(
            'subscriptions.cancel'
        );
    }

    /**
     * Renew
     */
    public function renew(
        User $user,
        Subscription $subscription
    ): bool {
        return $user->can(
            'subscriptions.renew'
        );
    }

    /**
     * Change Plan
     */
    public function changePlan(
        User $user,
        Subscription $subscription
    ): bool {
        return $user->can(
            'subscriptions.change-plan'
        );
    }
}