<?php

declare(strict_types=1);

namespace App\Domains\Purchases\Controllers;

use App\Domains\Purchases\Models\Purchase;
use App\Domains\Purchases\Requests\StorePurchaseRequest;
use App\Domains\Purchases\Requests\UpdatePurchaseRequest;
use App\Domains\Purchases\Resources\PurchaseResource;
use App\Domains\Purchases\Services\PurchaseService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    public function __construct(
        protected PurchaseService $service
    ) {}

    public function index()
    {
        return PurchaseResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StorePurchaseRequest $request
    ): PurchaseResource {

        return new PurchaseResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        Purchase $purchase
    ): PurchaseResource {

        return new PurchaseResource($purchase);
    }

    public function update(
        UpdatePurchaseRequest $request,
        Purchase $purchase
    ): PurchaseResource {

        return new PurchaseResource(
            $this->service->update($purchase, $request->validated())
        );
    }

    public function destroy(
        Purchase $purchase
    ): JsonResponse {

        $this->service->delete($purchase);

        return response()->json([
            'success' => true,
            'message' => 'Purchase deleted successfully',
        ]);
    }
}
