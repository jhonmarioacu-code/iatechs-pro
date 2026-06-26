<?php

declare(strict_types=1);

namespace App\Domains\Assets\Controllers;

use App\Domains\Assets\Models\Asset;
use App\Domains\Assets\Requests\StoreAssetRequest;
use App\Domains\Assets\Requests\UpdateAssetRequest;
use App\Domains\Assets\Resources\AssetResource;
use App\Domains\Assets\Services\AssetService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AssetController extends Controller
{
    public function __construct(
        protected AssetService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Asset::class);

        return AssetResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreAssetRequest $request
    ): AssetResource {
        $this->authorize('create', Asset::class);

        return new AssetResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        Asset $asset
    ): AssetResource {
        $this->authorize('view', $asset);

        return new AssetResource($asset);
    }

    public function update(
        UpdateAssetRequest $request,
        Asset $asset
    ): AssetResource {
        $this->authorize('update', $asset);

        return new AssetResource(
            $this->service->update($asset, $request->validated())
        );
    }

    public function destroy(
        Asset $asset
    ): JsonResponse {
        $this->authorize('delete', $asset);

        $this->service->delete($asset);

        return response()->json([
            'success' => true,
            'message' => 'Asset deleted successfully',
        ]);
    }
}
