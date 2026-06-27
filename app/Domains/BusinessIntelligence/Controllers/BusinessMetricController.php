<?php

declare(strict_types=1);

namespace App\Domains\BusinessIntelligence\Controllers;

use App\Domains\BusinessIntelligence\Models\BusinessMetric;
use App\Domains\BusinessIntelligence\Requests\StoreBusinessMetricRequest;
use App\Domains\BusinessIntelligence\Requests\UpdateBusinessMetricRequest;
use App\Domains\BusinessIntelligence\Resources\BusinessMetricResource;
use App\Domains\BusinessIntelligence\Services\BusinessMetricService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BusinessMetricController extends Controller
{
    public function __construct(
        protected BusinessMetricService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', BusinessMetric::class);

        return BusinessMetricResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreBusinessMetricRequest $request
    ): BusinessMetricResource {
        $this->authorize('create', BusinessMetric::class);

        return new BusinessMetricResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        BusinessMetric $businessMetric
    ): BusinessMetricResource {
        $this->authorize('view', $businessMetric);

        return new BusinessMetricResource($businessMetric);
    }

    public function update(
        UpdateBusinessMetricRequest $request,
        BusinessMetric $businessMetric
    ): BusinessMetricResource {
        $this->authorize('update', $businessMetric);

        return new BusinessMetricResource(
            $this->service->update($businessMetric, $request->validated())
        );
    }

    public function destroy(
        BusinessMetric $businessMetric
    ): JsonResponse {
        $this->authorize('delete', $businessMetric);

        $this->service->delete($businessMetric);

        return response()->json([
            'success' => true,
            'message' => 'BusinessMetric deleted successfully',
        ]);
    }
}
