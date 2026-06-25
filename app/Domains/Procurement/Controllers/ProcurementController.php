<?php

declare(strict_types=1);

namespace App\Domains\Procurement\Controllers;

use App\Domains\Procurement\Models\Procurement;
use App\Domains\Procurement\Requests\StoreProcurementRequest;
use App\Domains\Procurement\Requests\UpdateProcurementRequest;
use App\Domains\Procurement\Resources\ProcurementResource;
use App\Domains\Procurement\Services\ProcurementService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ProcurementController extends Controller
{
    public function __construct(
        protected ProcurementService $service
    ) {}

    public function index()
    {
        return ProcurementResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreProcurementRequest $request
    ): ProcurementResource {

        return new ProcurementResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        Procurement $procurement
    ): ProcurementResource {

        return new ProcurementResource($procurement);
    }

    public function update(
        UpdateProcurementRequest $request,
        Procurement $procurement
    ): ProcurementResource {

        return new ProcurementResource(
            $this->service->update($procurement, $request->validated())
        );
    }

    public function destroy(
        Procurement $procurement
    ): JsonResponse {

        $this->service->delete($procurement);

        return response()->json([
            'success' => true,
            'message' => 'Procurement deleted successfully',
        ]);
    }
}
