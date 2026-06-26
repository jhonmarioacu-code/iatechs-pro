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
        $this->authorize('viewAny', Purchase::class);

        return PurchaseResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StorePurchaseRequest $request
    ): PurchaseResource {
        $this->authorize('create', Purchase::class);

        return new PurchaseResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        Purchase $purchase
    ): PurchaseResource {
        $this->authorize('view', $purchase);

        return new PurchaseResource($purchase);
    }

    public function update(
        UpdatePurchaseRequest $request,
        Purchase $purchase
    ): PurchaseResource {
        $this->authorize('update', $purchase);

        return new PurchaseResource(
            $this->service->update($purchase, $request->validated())
        );
    }

    public function destroy(
        Purchase $purchase
    ): JsonResponse {
        $this->authorize('delete', $purchase);

        $this->service->delete($purchase);

        return response()->json([
            'success' => true,
            'message' => 'Purchase deleted successfully',
        ]);
    }
}
