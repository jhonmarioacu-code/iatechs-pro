<?php

declare(strict_types=1);

namespace App\Domains\Branches\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Domains\Branches\Models\Branch;
use App\Domains\Branches\Services\BranchService;

use App\Domains\Branches\Requests\StoreBranchRequest;
use App\Domains\Branches\Requests\UpdateBranchRequest;

use App\Domains\Branches\Resources\BranchResource;

class BranchController extends Controller
{
    public function __construct(
        private BranchService $service
    ) {}

    /**
     * Listado
     */
    public function index()
    {
        $this->authorize(
            'viewAny',
            Branch::class
        );

        return BranchResource::collection(
            $this->service->paginate()
        );
    }

    /**
     * Crear
     */
    public function store(
        StoreBranchRequest $request
    )
    {
        $this->authorize(
            'create',
            Branch::class
        );

        $branch = $this->service
            ->createBranch(
                $request->validated()
            );

        return (new BranchResource(
            $branch
        ))->response()->setStatusCode(201);
    }

    /**
     * Ver
     */
    public function show(
        Branch $branch
    ): BranchResource {

        $this->authorize(
            'view',
            $branch
        );

        return new BranchResource(
            $branch->load([
                'company'
            ])
        );
    }

    /**
     * Actualizar
     */
    public function update(
        UpdateBranchRequest $request,
        Branch $branch
    ): BranchResource {

        $this->authorize(
            'update',
            $branch
        );

        return new BranchResource(
            $this->service->updateBranch(
                $branch,
                $request->validated()
            )
        );
    }

    /**
     * Eliminar
     */
    public function destroy(
        Branch $branch
    ): JsonResponse {

        $this->authorize(
            'delete',
            $branch
        );

        $this->service
            ->deleteBranch(
                $branch
            );

        return response()->json([
            'success' => true,
            'message' => 'Sucursal eliminada correctamente.'
        ]);
    }

    /**
     * Activar
     */
    public function activate(
        Branch $branch
    ): BranchResource {

        $this->authorize(
            'activate',
            $branch
        );

        return new BranchResource(
            $this->service
                ->activateBranch(
                    $branch
                )
        );
    }

    /**
     * Desactivar
     */
    public function deactivate(
        Branch $branch
    ): BranchResource {

        $this->authorize(
            'deactivate',
            $branch
        );

        return new BranchResource(
            $this->service
                ->deactivateBranch(
                    $branch
                )
        );
    }
}