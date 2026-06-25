<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Controllers;

use App\Domains\Analytics\Models\Analytic;
use App\Domains\Analytics\Requests\StoreAnalyticRequest;
use App\Domains\Analytics\Requests\UpdateAnalyticRequest;
use App\Domains\Analytics\Resources\AnalyticResource;
use App\Domains\Analytics\Services\AnalyticService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AnalyticController extends Controller
{
    public function __construct(
        protected AnalyticService $service
    ) {}

    public function index()
    {
        return AnalyticResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreAnalyticRequest $request
    ): AnalyticResource {

        return new AnalyticResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        Analytic $analytic
    ): AnalyticResource {

        return new AnalyticResource($analytic);
    }

    public function update(
        UpdateAnalyticRequest $request,
        Analytic $analytic
    ): AnalyticResource {

        return new AnalyticResource(
            $this->service->update($analytic, $request->validated())
        );
    }

    public function destroy(
        Analytic $analytic
    ): JsonResponse {

        $this->service->delete($analytic);

        return response()->json([
            'success' => true,
            'message' => 'Analytic deleted successfully',
        ]);
    }
}
