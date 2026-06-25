<?php

declare(strict_types=1);

namespace App\Domains\Users\Policies;

use App\Domains\Users\Models\User;

class UserPolicy
{
    private function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function viewAny(
        User $user
    ): bool {
        return $user->can('users.view');
    }

    public function view(
        User $user,
        User $target
    ): bool {
        if (!$user->can('users.view')) {
            return false;
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->company_id === $target->company_id;
    }

    public function create(
        User $user
    ): bool {
        return $user->can('users.create');
    }

    public function update(
        User $user,
        User $target
    ): bool {
        if (!$user->can('users.update')) {
            return false;
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->company_id === $target->company_id;
    }

    public function delete(
        User $user,
        User $target
    ): bool {
        if (!$user->can('users.delete')) {
            return false;
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->company_id === $target->company_id;
    }
}
