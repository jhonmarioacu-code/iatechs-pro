<?php

declare(strict_types=1);

namespace App\Domains\Suppliers\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Suppliers\Models\Supplier;
use App\Domains\Suppliers\Services\SupplierService;
use App\Domains\Suppliers\Requests\StoreSupplierRequest;
use App\Domains\Suppliers\Requests\UpdateSupplierRequest;
use App\Domains\Suppliers\Resources\SupplierResource;

class SupplierController extends Controller
{
    public function __construct(
        private SupplierService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Supplier::class);

        return SupplierResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreSupplierRequest $request
    )
    {
        $this->authorize('create', Supplier::class);

        return new SupplierResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        Supplier $supplier
    )
    {
        $this->authorize('view', $supplier);

        return new SupplierResource(
            $supplier
        );
    }

    public function update(
        UpdateSupplierRequest $request,
        Supplier $supplier
    )
    {
        $this->authorize('update', $supplier);

        return new SupplierResource(
            $this->service->update(
                $supplier,
                $request->validated()
            )
        );
    }

    public function destroy(
        Supplier $supplier
    )
    {
        $this->authorize('delete', $supplier);

        $supplier->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function activate(
        Supplier $supplier
    )
    {
        $this->authorize('activate', $supplier);

        return new SupplierResource(
            $this->service->activate(
                $supplier
            )
        );
    }

    public function deactivate(
        Supplier $supplier
    )
    {
        $this->authorize('deactivate', $supplier);

        return new SupplierResource(
            $this->service->deactivate(
                $supplier
            )
        );
    }

    public function block(
        Supplier $supplier
    )
    {
        $this->authorize('block', $supplier);

        return new SupplierResource(
            $this->service->block(
                $supplier
            )
        );
    }
}
