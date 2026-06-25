<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Inventory\Models\InventoryMovement;
use App\Domains\Inventory\Services\InventoryService;
use App\Domains\Inventory\Requests\StoreInventoryMovementRequest;
use App\Domains\Inventory\Requests\UpdateInventoryMovementRequest;
use App\Domains\Inventory\Resources\InventoryMovementResource;

class InventoryController extends Controller
{
    public function __construct(
        private InventoryService $service
    ) {}

    public function index()
    {
        return InventoryMovementResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreInventoryMovementRequest $request
    )
    {
        return new InventoryMovementResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        InventoryMovement $inventoryMovement
    )
    {
        return new InventoryMovementResource(
            $inventoryMovement->load([
                'product',
                'branch',
                'user'
            ])
        );
    }

    public function update(
        UpdateInventoryMovementRequest $request,
        InventoryMovement $inventoryMovement
    )
    {
        $inventoryMovement->update(
            $request->validated()
        );

        return new InventoryMovementResource(
            $inventoryMovement->refresh()
        );
    }

    public function destroy(
        InventoryMovement $inventoryMovement
    )
    {
        $inventoryMovement->delete();

        return response()->json([
            'success' => true
        ]);
    }
}