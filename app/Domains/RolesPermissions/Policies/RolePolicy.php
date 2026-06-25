<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Policies;

use App\Domains\Users\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('roles.view');
    }

    public function view(
        User $user,
        Role $role
    ): bool {

        return $user->can('roles.view');
    }

    public function create(User $user): bool
    {
        return $user->can('roles.create');
    }

    public function update(
        User $user,
        Role $role
    ): bool {

        if ($role->name === 'super_admin') {
            return $user->hasRole('super_admin');
        }

        return $user->can('roles.update');
    }

    public function delete(
        User $user,
        Role $role
    ): bool {

        if ($role->name === 'super_admin') {
            return false;
        }

        return $user->can('roles.delete');
    }
}