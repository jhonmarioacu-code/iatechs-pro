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
        return PurchaseOrderResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StorePurchaseOrderRequest $request
    )
    {
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
        return new PurchaseOrderResource(
            $this->service->cancel(
                $purchaseOrder
            )
        );
    }
}