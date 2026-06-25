<?php

declare(strict_types=1);

namespace App\Domains\WorkOrders\Services;

use App\Domains\WorkOrders\Enums\WorkOrderStatus;
use App\Domains\WorkOrders\Models\WorkOrder;
use App\Domains\WorkOrders\Repositories\WorkOrderRepository;
use Illuminate\Support\Str;

class WorkOrderService
{
    public function __construct(
        private WorkOrderRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): WorkOrder
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? WorkOrderStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(WorkOrder $workOrder, array $data): WorkOrder
    {
        return $this->repository->update($workOrder, $data);
    }

    public function delete(WorkOrder $workOrder): bool
    {
        return $this->repository->delete($workOrder);
    }
}
