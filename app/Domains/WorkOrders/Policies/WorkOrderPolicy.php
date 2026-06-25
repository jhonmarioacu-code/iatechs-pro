<?php

declare(strict_types=1);

namespace App\Domains\WorkOrders\Policies;

use App\Domains\WorkOrders\Models\WorkOrder;
use App\Domains\Users\Models\User;

class WorkOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('work-orders.view');
    }

    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $user->can('work-orders.view')
            && $user->company_id === $workOrder->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('work-orders.create');
    }

    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $user->can('work-orders.update')
            && $user->company_id === $workOrder->company_id;
    }

    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return $user->can('work-orders.delete')
            && $user->company_id === $workOrder->company_id;
    }
}
