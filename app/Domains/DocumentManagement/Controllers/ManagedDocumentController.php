<?php

declare(strict_types=1);

namespace App\Domains\DocumentManagement\Controllers;

use App\Domains\DocumentManagement\Models\ManagedDocument;
use App\Domains\DocumentManagement\Requests\StoreManagedDocumentRequest;
use App\Domains\DocumentManagement\Requests\UpdateManagedDocumentRequest;
use App\Domains\DocumentManagement\Resources\ManagedDocumentResource;
use App\Domains\DocumentManagement\Services\ManagedDocumentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ManagedDocumentController extends Controller
{
    public function __construct(
        protected ManagedDocumentService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', ManagedDocument::class);

        return ManagedDocumentResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreManagedDocumentRequest $request
    ): ManagedDocumentResource {
        $this->authorize('create', ManagedDocument::class);

        return new ManagedDocumentResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        ManagedDocument $managedDocument
    ): ManagedDocumentResource {
        $this->authorize('view', $managedDocument);

        return new ManagedDocumentResource($managedDocument);
    }

    public function update(
        UpdateManagedDocumentRequest $request,
        ManagedDocument $managedDocument
    ): ManagedDocumentResource {
        $this->authorize('update', $managedDocument);

        return new ManagedDocumentResource(
            $this->service->update($managedDocument, $request->validated())
        );
    }

    public function destroy(
        ManagedDocument $managedDocument
    ): JsonResponse {
        $this->authorize('delete', $managedDocument);

        $this->service->delete($managedDocument);

        return response()->json([
            'success' => true,
            'message' => 'ManagedDocument deleted successfully',
        ]);
    }
}
