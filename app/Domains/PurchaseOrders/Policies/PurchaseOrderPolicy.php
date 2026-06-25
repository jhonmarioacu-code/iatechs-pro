<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Policies;

use App\Domains\Users\Models\User;
use App\Domains\PurchaseOrders\Models\PurchaseOrder;

class PurchaseOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('purchase_orders.view');
    }

    public function view(
        User $user,
        PurchaseOrder $purchaseOrder
    ): bool {

        return $user->company_id ===
            $purchaseOrder->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('purchase_orders.create');
    }

    public function update(User $user): bool
    {
        return $user->can('purchase_orders.update');
    }

    public function approve(User $user): bool
    {
        return $user->can('purchase_orders.approve');
    }

    public function receive(User $user): bool
    {
        return $user->can('purchase_orders.receive');
    }

    public function cancel(User $user): bool
    {
        return $user->can('purchase_orders.cancel');
    }
}