<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Services;

use App\Domains\RolesPermissions\Repositories\RoleRepository;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Exception;

class RoleService
{
    public function __construct(
        private RoleRepository $repository
    ) {}

    /**
     * Listado paginado
     */
    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * Crear rol
     */
    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {

            $role = $this->repository->create([
                'name' => $data['name'],
                'guard_name' => 'web',
            ]);

            if (!empty($data['permissions'])) {
                $role->syncPermissions(
                    $data['permissions']
                );
            }

            return $role->fresh(['permissions']);
        });
    }

    /**
     * Actualizar rol
     */
    public function updateRole(
        Role $role,
        array $data
    ): Role {

        return DB::transaction(function () use (
            $role,
            $data
        ) {

            $role = $this->repository->update(
                $role,
                [
                    'name' => $data['name']
                ]
            );

            if (isset($data['permissions'])) {
                $role->syncPermissions(
                    $data['permissions']
                );
            }

            return $role->fresh(['permissions']);
        });
    }

    /**
     * Eliminar rol
     */
    public function deleteRole(
        Role $role
    ): bool {

        if ($role->name === 'super_admin') {

            throw new Exception(
                'No se puede eliminar el rol super_admin.'
            );
        }

        return $this->repository->delete($role);
    }

    /**
     * Asignar permiso
     */
    public function assignPermission(
        Role $role,
        string $permission
    ): Role {

        $role->givePermissionTo(
            $permission
        );

        return $role->fresh(['permissions']);
    }

    /**
     * Remover permiso
     */
    public function revokePermission(
        Role $role,
        string $permission
    ): Role {

        $role->revokePermissionTo(
            $permission
        );

        return $role->fresh(['permissions']);
    }

    /**
     * Sincronizar permisos
     */
    public function syncPermissions(
        Role $role,
        array $permissions
    ): Role {

        $role->syncPermissions(
            $permissions
        );

        return $role->fresh(['permissions']);
    }

    /**
     * Buscar rol por ID
     */
    public function find(
        int $id
    ): ?Role {

        return $this->repository->find($id);
    }
}