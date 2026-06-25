<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\PurchaseOrders\Models\PurchaseOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PurchaseOrderRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return PurchaseOrder::query()
            ->with([
                'supplier',
                'items.product',
                'creator'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?PurchaseOrder {

        return PurchaseOrder::with([
            'supplier',
            'items.product',
            'creator'
        ])->find($id);
    }

    public function create(
        array $data
    ): PurchaseOrder {

        return PurchaseOrder::create($data);
    }

    public function update(
        PurchaseOrder $purchaseOrder,
        array $data
    ): PurchaseOrder {

        $purchaseOrder->update($data);

        return $purchaseOrder->refresh();
    }

    public function delete(
        PurchaseOrder $purchaseOrder
    ): bool {

        return (bool) $purchaseOrder->delete();
    }
}