<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Inventory\Models\InventoryMovement;

class InventoryPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'inventory.view'
        );
    }

    public function view(
        User $user,
        InventoryMovement $movement
    ): bool {

        return
            $user->can('inventory.view')
            &&
            $user->company_id ===
            $movement->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'inventory.create'
        );
    }

    public function update(
        User $user,
        InventoryMovement $movement
    ): bool {

        return
            $user->can('inventory.update')
            &&
            $user->company_id ===
            $movement->company_id;
    }

    public function delete(
        User $user,
        InventoryMovement $movement
    ): bool {

        return
            $user->can('inventory.delete')
            &&
            $user->company_id ===
            $movement->company_id;
    }
}