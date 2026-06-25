<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    use ProvidesRepositoryDefaults;

    protected function repositoryModelClass(): string
    {
        return Role::class;
    }

    /**
     * Listado paginado
     */
    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Obtener todos los roles
     */
    public function all(): Collection
    {
        return Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->get();
    }

    /**
     * Buscar por ID
     */
    public function find(
        int $id
    ): ?Role {

        return Role::query()
            ->with('permissions')
            ->find($id);
    }

    /**
     * Buscar por nombre
     */
    public function findByName(
        string $name,
        string $guard = 'web'
    ): ?Role {

        return Role::query()
            ->where('name', $name)
            ->where('guard_name', $guard)
            ->first();
    }

    /**
     * Crear rol
     */
    public function create(
        array $data
    ): Role {
        $role = new Role($data);
        $role->save();

        return $role;
    }

    /**
     * Actualizar rol
     */
    public function update(
        Role $role,
        array $data
    ): Role {

        $role->update($data);

        return $role->refresh();
    }

    /**
     * Eliminar rol
     */
    public function delete(
        Role $role
    ): bool {

        return (bool) $role->delete();
    }

    /**
     * Verificar existencia
     */
    public function exists(
        string $name,
        string $guard = 'web'
    ): bool {

        return Role::query()
            ->where('name', $name)
            ->where('guard_name', $guard)
            ->exists();
    }
}
