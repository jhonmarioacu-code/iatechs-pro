<?php

declare(strict_types=1);

namespace App\Domains\HumanResources\Policies;

use App\Domains\HumanResources\Models\HumanResource;
use App\Domains\Users\Models\User;

class HumanResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('human-resources.view');
    }

    public function view(User $user, HumanResource $humanResource): bool
    {
        return $user->can('human-resources.view')
            && $user->company_id === $humanResource->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('human-resources.create');
    }

    public function update(User $user, HumanResource $humanResource): bool
    {
        return $user->can('human-resources.update')
            && $user->company_id === $humanResource->company_id;
    }

    public function delete(User $user, HumanResource $humanResource): bool
    {
        return $user->can('human-resources.delete')
            && $user->company_id === $humanResource->company_id;
    }
}
