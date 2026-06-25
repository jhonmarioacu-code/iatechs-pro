<?php

declare(strict_types=1);

namespace App\Domains\WorkOrders\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\WorkOrders\Models\WorkOrder;

class WorkOrderRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return WorkOrder::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): WorkOrder
    {
        return WorkOrder::create($data);
    }

    public function update(WorkOrder $workOrder, array $data): WorkOrder
    {
        $workOrder->update($data);

        return $workOrder->refresh();
    }

    public function delete(WorkOrder $workOrder): bool
    {
        return (bool) $workOrder->delete();
    }
}
