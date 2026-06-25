<?php

declare(strict_types=1);

namespace App\Domains\WorkOrders\Controllers;

use App\Domains\WorkOrders\Models\WorkOrder;
use App\Domains\WorkOrders\Requests\StoreWorkOrderRequest;
use App\Domains\WorkOrders\Requests\UpdateWorkOrderRequest;
use App\Domains\WorkOrders\Resources\WorkOrderResource;
use App\Domains\WorkOrders\Services\WorkOrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class WorkOrderController extends Controller
{
    public function __construct(
        protected WorkOrderService $service
    ) {}

    public function index()
    {
        return WorkOrderResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreWorkOrderRequest $request
    ): WorkOrderResource {

        return new WorkOrderResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        WorkOrder $workOrder
    ): WorkOrderResource {

        return new WorkOrderResource($workOrder);
    }

    public function update(
        UpdateWorkOrderRequest $request,
        WorkOrder $workOrder
    ): WorkOrderResource {

        return new WorkOrderResource(
            $this->service->update($workOrder, $request->validated())
        );
    }

    public function destroy(
        WorkOrder $workOrder
    ): JsonResponse {

        $this->service->delete($workOrder);

        return response()->json([
            'success' => true,
            'message' => 'WorkOrder deleted successfully',
        ]);
    }
}
