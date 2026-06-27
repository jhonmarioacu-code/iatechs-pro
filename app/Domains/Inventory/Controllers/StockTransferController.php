<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Inventory\Models\StockTransfer;
use App\Domains\Inventory\Services\StockTransferService;
use App\Domains\Inventory\Requests\StoreStockTransferRequest;
use App\Domains\Inventory\Requests\UpdateStockTransferRequest;
use App\Domains\Inventory\Resources\StockTransferResource;

class StockTransferController extends Controller
{
    public function __construct(
        private StockTransferService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', StockTransfer::class);

        return StockTransferResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreStockTransferRequest $request
    )
    {
        $this->authorize('create', StockTransfer::class);

        return new StockTransferResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        StockTransfer $stockTransfer
    )
    {
        $this->authorize('view', $stockTransfer);

        return new StockTransferResource(
            $stockTransfer->load([
                'product',
                'fromBranch',
                'toBranch',
                'requester',
                'approver'
            ])
        );
    }

    public function update(
        UpdateStockTransferRequest $request,
        StockTransfer $stockTransfer
    )
    {
        $this->authorize('update', $stockTransfer);

        $stockTransfer->update(
            $request->validated()
        );

        return new StockTransferResource(
            $stockTransfer->refresh()
        );
    }

    public function approve(
        StockTransfer $stockTransfer
    )
    {
        $this->authorize('approve', $stockTransfer);

        return new StockTransferResource(
            $this->service->approve(
                $stockTransfer,
                auth()->id()
            )
        );
    }

    public function complete(
        StockTransfer $stockTransfer
    )
    {
        $this->authorize('complete', $stockTransfer);

        return new StockTransferResource(
            $this->service->complete(
                $stockTransfer
            )
        );
    }

    public function cancel(
        StockTransfer $stockTransfer
    )
    {
        $this->authorize('cancel', $stockTransfer);

        return new StockTransferResource(
            $this->service->cancel(
                $stockTransfer
            )
        );
    }
}
