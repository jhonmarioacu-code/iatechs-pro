<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\GoodsReceipts\Models\GoodsReceipt;
use App\Domains\GoodsReceipts\Services\GoodsReceiptService;
use App\Domains\GoodsReceipts\Requests\StoreGoodsReceiptRequest;
use App\Domains\GoodsReceipts\Requests\UpdateGoodsReceiptRequest;
use App\Domains\GoodsReceipts\Resources\GoodsReceiptResource;

class GoodsReceiptController extends Controller
{
    public function __construct(
        private GoodsReceiptService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', GoodsReceipt::class);

        return GoodsReceiptResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreGoodsReceiptRequest $request
    )
    {
        $this->authorize('create', GoodsReceipt::class);

        return new GoodsReceiptResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        GoodsReceipt $goodsReceipt
    )
    {
        $this->authorize('view', $goodsReceipt);

        return new GoodsReceiptResource(
            $goodsReceipt->load([
                'supplier',
                'purchaseOrder',
                'items.product'
            ])
        );
    }

    public function update(
        UpdateGoodsReceiptRequest $request,
        GoodsReceipt $goodsReceipt
    )
    {
        $this->authorize('update', $goodsReceipt);

        $goodsReceipt->update(
            $request->validated()
        );

        return new GoodsReceiptResource(
            $goodsReceipt->refresh()
        );
    }

    public function receive(
        GoodsReceipt $goodsReceipt
    )
    {
        $this->authorize('receive', $goodsReceipt);

        return new GoodsReceiptResource(
            $this->service->receive(
                $goodsReceipt,
                request('branch_id')
            )
        );
    }
}
