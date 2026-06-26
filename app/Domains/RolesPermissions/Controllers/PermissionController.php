<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use App\Domains\RolesPermissions\Services\PermissionService;
use App\Domains\RolesPermissions\Requests\StorePermissionRequest;
use App\Domains\RolesPermissions\Requests\UpdatePermissionRequest;
use App\Domains\RolesPermissions\Resources\PermissionResource;

use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(
        private PermissionService $service
    ) {}

    /**
     * Listado de permisos
     */
    public function index()
    {
        $this->authorize('viewAny', Permission::class);

        $permissions = $this->service->paginate();

        return PermissionResource::collection(
            $permissions
        );
    }

    /**
     * Crear permiso
     */
    public function store(
        StorePermissionRequest $request
    ): PermissionResource {
        $this->authorize('create', Permission::class);

        $permission = $this->service
            ->createPermission(
                $request->validated()
            );

        return new PermissionResource(
            $permission
        );
    }

    /**
     * Ver permiso
     */
    public function show(
        Permission $permission
    ): PermissionResource {
        $this->authorize('view', $permission);

        return new PermissionResource(
            $permission
        );
    }

    /**
     * Actualizar permiso
     */
    public function update(
        UpdatePermissionRequest $request,
        Permission $permission
    ): PermissionResource {
        $this->authorize('update', $permission);

        $permission = $this->service
            ->updatePermission(
                $permission,
                $request->validated()
            );

        return new PermissionResource(
            $permission
        );
    }

    /**
     * Eliminar permiso
     */
    public function destroy(
        Permission $permission
    ): JsonResponse {
        $this->authorize('delete', $permission);

        $this->service
            ->deletePermission(
                $permission
            );

        return response()->json([
            'success' => true,
            'message' => 'Permiso eliminado correctamente.'
        ]);
    }
}
