<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Inventory\Models\StockTransfer;

class StockTransferPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('inventory.view');
    }

    public function view(
        User $user,
        StockTransfer $transfer
    ): bool {

        return $user->company_id ===
            $transfer->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can(
            'inventory.create'
        );
    }

    public function update(User $user): bool
    {
        return $user->can(
            'inventory.update'
        );
    }

    public function approve(User $user): bool
    {
        return $user->can(
            'inventory.approve'
        );
    }

    public function complete(User $user): bool
    {
        return $user->can(
            'inventory.complete'
        );
    }

    public function cancel(User $user): bool
    {
        return $user->can(
            'inventory.cancel'
        );
    }
}
