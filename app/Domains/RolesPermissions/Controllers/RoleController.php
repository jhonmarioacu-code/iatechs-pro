<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use App\Domains\RolesPermissions\Services\RoleService;
use App\Domains\RolesPermissions\Requests\StoreRoleRequest;
use App\Domains\RolesPermissions\Requests\UpdateRoleRequest;
use App\Domains\RolesPermissions\Resources\RoleResource;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        private RoleService $service
    ) {}

    /**
     * Listado de roles
     */
    public function index()
    {
        $roles = $this->service->paginate();

        return RoleResource::collection($roles);
    }

    /**
     * Crear rol
     */
    public function store(
        StoreRoleRequest $request
    ): RoleResource {

        $role = $this->service->createRole(
            $request->validated()
        );

        return new RoleResource($role);
    }

    /**
     * Ver rol
     */
    public function show(
        Role $role
    ): RoleResource {

        return new RoleResource(
            $role->load('permissions')
        );
    }

    /**
     * Actualizar rol
     */
    public function update(
        UpdateRoleRequest $request,
        Role $role
    ): RoleResource {

        $role = $this->service->updateRole(
            $role,
            $request->validated()
        );

        return new RoleResource($role);
    }

    /**
     * Eliminar rol
     */
    public function destroy(
        Role $role
    ): JsonResponse {

        $this->service->deleteRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Rol eliminado correctamente.'
        ]);
    }

    /**
     * Sincronizar permisos
     */
    public function syncPermissions(
        Role $role
    ): RoleResource {

        $permissions = request()->input(
            'permissions',
            []
        );

        $role = $this->service
            ->syncPermissions(
                $role,
                $permissions
            );

        return new RoleResource($role);
    }
}