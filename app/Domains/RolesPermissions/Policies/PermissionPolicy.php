<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Policies;

use App\Domains\Users\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('permissions.view');
    }

    public function view(
        User $user,
        Permission $permission
    ): bool {

        return $user->can('permissions.view');
    }

    public function create(User $user): bool
    {
        return $user->can('permissions.create');
    }

    public function update(
        User $user,
        Permission $permission
    ): bool {

        return $user->can('permissions.update');
    }

    public function delete(
        User $user,
        Permission $permission
    ): bool {

        return $user->can('permissions.delete');
    }
}