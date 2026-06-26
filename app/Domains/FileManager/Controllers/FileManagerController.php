<?php

declare(strict_types=1);

namespace App\Domains\FileManager\Controllers;

use App\Domains\FileManager\Models\FileManager;
use App\Domains\FileManager\Requests\StoreFileManagerRequest;
use App\Domains\FileManager\Requests\UpdateFileManagerRequest;
use App\Domains\FileManager\Resources\FileManagerResource;
use App\Domains\FileManager\Services\FileManagerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FileManagerController extends Controller
{
    public function __construct(
        protected FileManagerService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', FileManager::class);

        return FileManagerResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreFileManagerRequest $request
    ): FileManagerResource {
        $this->authorize('create', FileManager::class);

        return new FileManagerResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        FileManager $fileManager
    ): FileManagerResource {
        $this->authorize('view', $fileManager);

        return new FileManagerResource($fileManager);
    }

    public function update(
        UpdateFileManagerRequest $request,
        FileManager $fileManager
    ): FileManagerResource {
        $this->authorize('update', $fileManager);

        return new FileManagerResource(
            $this->service->update($fileManager, $request->validated())
        );
    }

    public function destroy(
        FileManager $fileManager
    ): JsonResponse {
        $this->authorize('delete', $fileManager);

        $this->service->delete($fileManager);

        return response()->json([
            'success' => true,
            'message' => 'FileManager deleted successfully',
        ]);
    }
}
