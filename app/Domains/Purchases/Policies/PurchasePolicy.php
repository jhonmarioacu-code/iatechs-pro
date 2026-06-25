<?php

declare(strict_types=1);

namespace App\Domains\Purchases\Policies;

use App\Domains\Purchases\Models\Purchase;
use App\Domains\Users\Models\User;

class PurchasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('purchases.view');
    }

    public function view(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.view')
            && $user->company_id === $purchase->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('purchases.create');
    }

    public function update(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.update')
            && $user->company_id === $purchase->company_id;
    }

    public function delete(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.delete')
            && $user->company_id === $purchase->company_id;
    }
}
