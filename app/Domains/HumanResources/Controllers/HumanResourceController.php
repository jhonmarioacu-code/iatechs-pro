<?php

declare(strict_types=1);

namespace App\Domains\HumanResources\Controllers;

use App\Domains\HumanResources\Models\HumanResource;
use App\Domains\HumanResources\Requests\StoreHumanResourceRequest;
use App\Domains\HumanResources\Requests\UpdateHumanResourceRequest;
use App\Domains\HumanResources\Resources\HumanResourceResource;
use App\Domains\HumanResources\Services\HumanResourceService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class HumanResourceController extends Controller
{
    public function __construct(
        protected HumanResourceService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', HumanResource::class);

        return HumanResourceResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreHumanResourceRequest $request
    ): HumanResourceResource {
        $this->authorize('create', HumanResource::class);

        return new HumanResourceResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        HumanResource $humanResource
    ): HumanResourceResource {
        $this->authorize('view', $humanResource);

        return new HumanResourceResource($humanResource);
    }

    public function update(
        UpdateHumanResourceRequest $request,
        HumanResource $humanResource
    ): HumanResourceResource {
        $this->authorize('update', $humanResource);

        return new HumanResourceResource(
            $this->service->update($humanResource, $request->validated())
        );
    }

    public function destroy(
        HumanResource $humanResource
    ): JsonResponse {
        $this->authorize('delete', $humanResource);

        $this->service->delete($humanResource);

        return response()->json([
            'success' => true,
            'message' => 'HumanResource deleted successfully',
        ]);
    }
}
