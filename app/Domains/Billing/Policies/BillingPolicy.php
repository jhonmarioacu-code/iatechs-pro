<?php

declare(strict_types=1);

namespace App\Domains\Billing\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Billing\Models\Billing;

class BillingPolicy
{
    /**
     * View Any
     */
    public function viewAny(
        User $user
    ): bool {
        return $user->can(
            'billings.view'
        );
    }

    /**
     * View
     */
    public function view(
        User $user,
        Billing $billing
    ): bool {
        return $user->can(
            'billings.view'
        );
    }

    /**
     * Create
     */
    public function create(
        User $user
    ): bool {
        return $user->can(
            'billings.create'
        );
    }

    /**
     * Update
     */
    public function update(
        User $user,
        Billing $billing
    ): bool {
        return $user->can(
            'billings.update'
        );
    }

    /**
     * Delete
     */
    public function delete(
        User $user,
        Billing $billing
    ): bool {
        return $user->can(
            'billings.delete'
        );
    }

    /**
     * Mark Paid
     */
    public function markPaid(
        User $user,
        Billing $billing
    ): bool {
        return $user->can(
            'billings.mark-paid'
        );
    }

    /**
     * Mark Failed
     */
    public function markFailed(
        User $user,
        Billing $billing
    ): bool {
        return $user->can(
            'billings.mark-failed'
        );
    }

    /**
     * Cancel
     */
    public function cancel(
        User $user,
        Billing $billing
    ): bool {
        return $user->can(
            'billings.cancel'
        );
    }

    /**
     * Refund
     */
    public function refund(
        User $user,
        Billing $billing
    ): bool {
        return $user->can(
            'billings.refund'
        );
    }
}