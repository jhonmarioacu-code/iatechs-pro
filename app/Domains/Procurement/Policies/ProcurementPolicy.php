<?php

declare(strict_types=1);

namespace App\Domains\Procurement\Policies;

use App\Domains\Procurement\Models\Procurement;
use App\Domains\Users\Models\User;

class ProcurementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('procurement.view');
    }

    public function view(User $user, Procurement $procurement): bool
    {
        return $user->can('procurement.view')
            && $user->company_id === $procurement->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('procurement.create');
    }

    public function update(User $user, Procurement $procurement): bool
    {
        return $user->can('procurement.update')
            && $user->company_id === $procurement->company_id;
    }

    public function delete(User $user, Procurement $procurement): bool
    {
        return $user->can('procurement.delete')
            && $user->company_id === $procurement->company_id;
    }
}
