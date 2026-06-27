<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Policies;

use App\Domains\Users\Models\User;

class AccountingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('accounting.view');
    }

    public function view(User $user): bool
    {
        return $user->can('accounting.view');
    }

    public function create(User $user): bool
    {
        return $user->can('accounting.create');
    }

    public function update(User $user): bool
    {
        return $user->can('accounting.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('accounting.update');
    }

    public function post(User $user): bool
    {
        return $user->can('accounting.post');
    }

    public function cancel(User $user): bool
    {
        return $user->can('accounting.cancel');
    }
}
