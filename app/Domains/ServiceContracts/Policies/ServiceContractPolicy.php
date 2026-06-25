<?php

declare(strict_types=1);

namespace App\Domains\ServiceContracts\Policies;

use App\Domains\ServiceContracts\Models\ServiceContract;
use App\Domains\Users\Models\User;

class ServiceContractPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('service-contracts.view');
    }

    public function view(User $user, ServiceContract $serviceContract): bool
    {
        return $user->can('service-contracts.view')
            && $user->company_id === $serviceContract->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('service-contracts.create');
    }

    public function update(User $user, ServiceContract $serviceContract): bool
    {
        return $user->can('service-contracts.update')
            && $user->company_id === $serviceContract->company_id;
    }

    public function delete(User $user, ServiceContract $serviceContract): bool
    {
        return $user->can('service-contracts.delete')
            && $user->company_id === $serviceContract->company_id;
    }
}
