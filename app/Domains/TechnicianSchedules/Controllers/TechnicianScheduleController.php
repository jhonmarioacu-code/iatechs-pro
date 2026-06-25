<?php

declare(strict_types=1);

namespace App\Domains\TechnicianSchedules\Controllers;

use App\Domains\TechnicianSchedules\Models\TechnicianSchedule;
use App\Domains\TechnicianSchedules\Requests\StoreTechnicianScheduleRequest;
use App\Domains\TechnicianSchedules\Requests\UpdateTechnicianScheduleRequest;
use App\Domains\TechnicianSchedules\Resources\TechnicianScheduleResource;
use App\Domains\TechnicianSchedules\Services\TechnicianScheduleService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TechnicianScheduleController extends Controller
{
    public function __construct(
        protected TechnicianScheduleService $service
    ) {}

    public function index()
    {
        return TechnicianScheduleResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreTechnicianScheduleRequest $request
    ): TechnicianScheduleResource {

        return new TechnicianScheduleResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        TechnicianSchedule $technicianSchedule
    ): TechnicianScheduleResource {

        return new TechnicianScheduleResource($technicianSchedule);
    }

    public function update(
        UpdateTechnicianScheduleRequest $request,
        TechnicianSchedule $technicianSchedule
    ): TechnicianScheduleResource {

        return new TechnicianScheduleResource(
            $this->service->update($technicianSchedule, $request->validated())
        );
    }

    public function destroy(
        TechnicianSchedule $technicianSchedule
    ): JsonResponse {

        $this->service->delete($technicianSchedule);

        return response()->json([
            'success' => true,
            'message' => 'TechnicianSchedule deleted successfully',
        ]);
    }
}
