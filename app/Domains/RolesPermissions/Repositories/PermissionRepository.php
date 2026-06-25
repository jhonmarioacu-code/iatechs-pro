<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository
{
    use ProvidesRepositoryDefaults;

    protected function repositoryModelClass(): string
    {
        return Permission::class;
    }

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Permission::query()
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return Permission::query()
            ->orderBy('name')
            ->get();
    }

    public function find(
        int $id
    ): ?Permission {

        return Permission::find($id);
    }

    public function findByName(
        string $name,
        string $guard = 'web'
    ): ?Permission {

        return Permission::query()
            ->where('name', $name)
            ->where('guard_name', $guard)
            ->first();
    }

    public function create(
        array $data
    ): Permission {
        $permission = new Permission($data);
        $permission->save();

        return $permission;
    }

    public function update(
        Permission $permission,
        array $data
    ): Permission {

        $permission->update($data);

        return $permission->refresh();
    }

    public function delete(
        Permission $permission
    ): bool {

        return (bool) $permission->delete();
    }

    public function exists(
        string $name,
        string $guard = 'web'
    ): bool {

        return Permission::query()
            ->where('name', $name)
            ->where('guard_name', $guard)
            ->exists();
    }
}
