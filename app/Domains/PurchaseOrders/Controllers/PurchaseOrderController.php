<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\PurchaseOrders\Models\PurchaseOrder;
use App\Domains\PurchaseOrders\Services\PurchaseOrderService;
use App\Domains\PurchaseOrders\Requests\StorePurchaseOrderRequest;
use App\Domains\PurchaseOrders\Requests\UpdatePurchaseOrderRequest;
use App\Domains\PurchaseOrders\Resources\PurchaseOrderResource;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private PurchaseOrderService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', PurchaseOrder::class);

        return PurchaseOrderResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StorePurchaseOrderRequest $request
    )
    {
        $this->authorize('create', PurchaseOrder::class);

        return new PurchaseOrderResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        PurchaseOrder $purchaseOrder
    )
    {
        $this->authorize('view', $purchaseOrder);

        return new PurchaseOrderResource(
            $purchaseOrder->load([
                'supplier',
                'items.product',
                'creator'
            ])
        );
    }

    public function update(
        UpdatePurchaseOrderRequest $request,
        PurchaseOrder $purchaseOrder
    )
    {
        $this->authorize('update', $purchaseOrder);

        $purchaseOrder->update(
            $request->validated()
        );

        return new PurchaseOrderResource(
            $purchaseOrder->refresh()
        );
    }

    public function approve(
        PurchaseOrder $purchaseOrder
    )
    {
        $this->authorize('approve', $purchaseOrder);

        return new PurchaseOrderResource(
            $this->service->approve(
                $purchaseOrder
            )
        );
    }

    public function receive(
        PurchaseOrder $purchaseOrder
    )
    {
        $this->authorize('receive', $purchaseOrder);

        return new PurchaseOrderResource(
            $this->service->markAsReceived(
                $purchaseOrder
            )
        );
    }

    public function cancel(
        PurchaseOrder $purchaseOrder
    )
    {
        $this->authorize('cancel', $purchaseOrder);

        return new PurchaseOrderResource(
            $this->service->cancel(
                $purchaseOrder
            )
        );
    }
}
