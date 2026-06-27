<?php

declare(strict_types=1);

namespace App\Domains\SystemSettings\Controllers;

use App\Domains\SystemSettings\Models\SystemSetting;
use App\Domains\SystemSettings\Requests\StoreSystemSettingRequest;
use App\Domains\SystemSettings\Requests\UpdateSystemSettingRequest;
use App\Domains\SystemSettings\Resources\SystemSettingResource;
use App\Domains\SystemSettings\Services\SystemSettingService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SystemSettingController extends Controller
{
    public function __construct(
        protected SystemSettingService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', SystemSetting::class);

        return SystemSettingResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreSystemSettingRequest $request
    ): SystemSettingResource {
        $this->authorize('create', SystemSetting::class);

        return new SystemSettingResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        SystemSetting $systemSetting
    ): SystemSettingResource {
        $this->authorize('view', $systemSetting);

        return new SystemSettingResource($systemSetting);
    }

    public function update(
        UpdateSystemSettingRequest $request,
        SystemSetting $systemSetting
    ): SystemSettingResource {
        $this->authorize('update', $systemSetting);

        return new SystemSettingResource(
            $this->service->update($systemSetting, $request->validated())
        );
    }

    public function destroy(
        SystemSetting $systemSetting
    ): JsonResponse {
        $this->authorize('delete', $systemSetting);

        $this->service->delete($systemSetting);

        return response()->json([
            'success' => true,
            'message' => 'SystemSetting deleted successfully',
        ]);
    }
}
