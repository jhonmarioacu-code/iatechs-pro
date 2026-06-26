<?php

declare(strict_types=1);

namespace App\Domains\Projects\Controllers;

use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Requests\StoreProjectRequest;
use App\Domains\Projects\Requests\UpdateProjectRequest;
use App\Domains\Projects\Resources\ProjectResource;
use App\Domains\Projects\Services\ProjectService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Project::class);

        return ProjectResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreProjectRequest $request
    ): ProjectResource {
        $this->authorize('create', Project::class);

        return new ProjectResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        Project $project
    ): ProjectResource {
        $this->authorize('view', $project);

        return new ProjectResource($project);
    }

    public function update(
        UpdateProjectRequest $request,
        Project $project
    ): ProjectResource {
        $this->authorize('update', $project);

        return new ProjectResource(
            $this->service->update($project, $request->validated())
        );
    }

    public function destroy(
        Project $project
    ): JsonResponse {
        $this->authorize('delete', $project);

        $this->service->delete($project);

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }
}
