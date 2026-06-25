<?php

declare(strict_types=1);

namespace App\Domains\Plans\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Plans\Models\Plan;

class PlanPolicy
{
    /**
     * View Any Plans
     */
    public function viewAny(
        User $user
    ): bool {
        return $user->can('plans.view');
    }

    /**
     * View Plan
     */
    public function view(
        User $user,
        Plan $plan
    ): bool {
        return $user->can('plans.view');
    }

    /**
     * Create Plan
     */
    public function create(
        User $user
    ): bool {
        return $user->can('plans.create');
    }

    /**
     * Update Plan
     */
    public function update(
        User $user,
        Plan $plan
    ): bool {
        return $user->can('plans.update');
    }

    /**
     * Delete Plan
     */
    public function delete(
        User $user,
        Plan $plan
    ): bool {
        return $user->can('plans.delete');
    }

    /**
     * Activate Plan
     */
    public function activate(
        User $user,
        Plan $plan
    ): bool {
        return $user->can('plans.activate');
    }

    /**
     * Deactivate Plan
     */
    public function deactivate(
        User $user,
        Plan $plan
    ): bool {
        return $user->can('plans.deactivate');
    }

    /**
     * Restore Plan
     */
    public function restore(
        User $user,
        Plan $plan
    ): bool {
        return $user->can('plans.restore');
    }

    /**
     * Force Delete Plan
     */
    public function forceDelete(
        User $user,
        Plan $plan
    ): bool {
        return $user->can('plans.force-delete');
    }
}