<?php

declare(strict_types=1);

namespace App\Domains\Plans\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;

use App\Domains\Plans\Models\Plan;

use App\Domains\Plans\Services\PlanService;

use App\Domains\Plans\Requests\StorePlanRequest;
use App\Domains\Plans\Requests\UpdatePlanRequest;

use App\Domains\Plans\Resources\PlanResource;

class PlanController extends Controller
{
    public function __construct(
        protected PlanService $service
    ) {}

    /**
     * List Plans
     */
    public function index()
    {
        return PlanResource::collection(
            $this->service->paginate()
        );
    }

    /**
     * Store Plan
     */
    public function store(
        StorePlanRequest $request
    ): PlanResource {

        $plan = $this->service->create(
            $request->validated()
        );

        return new PlanResource(
            $plan
        );
    }

    /**
     * Show Plan
     */
    public function show(
        Plan $plan
    ): PlanResource {

        return new PlanResource(
            $plan
        );
    }

    /**
     * Update Plan
     */
    public function update(
        UpdatePlanRequest $request,
        Plan $plan
    ): PlanResource {

        $plan = $this->service->update(
            $plan,
            $request->validated()
        );

        return new PlanResource(
            $plan
        );
    }

    /**
     * Delete Plan
     */
    public function destroy(
        Plan $plan
    ): JsonResponse {

        $this->service->delete(
            $plan
        );

        return response()->json([
            'success' => true,
            'message' => 'Plan deleted successfully'
        ]);
    }

    /**
     * Activate Plan
     */
    public function activate(
        Plan $plan
    ): PlanResource {

        return new PlanResource(
            $this->service->activate(
                $plan
            )
        );
    }

    /**
     * Deactivate Plan
     */
    public function deactivate(
        Plan $plan
    ): PlanResource {

        return new PlanResource(
            $this->service->deactivate(
                $plan
            )
        );
    }
}