<?php

declare(strict_types=1);

namespace App\Domains\RolesPermissions\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Domains\RolesPermissions\Repositories\PermissionRepository;

class PermissionService
{
    public function __construct(
        private PermissionRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    ) {
        return $this->repository->paginate($perPage);
    }

    public function createPermission(
        array $data
    ): Permission {

        return DB::transaction(function () use ($data) {

            $data['guard_name'] = 'web';

            return $this->repository
                ->create($data);
        });
    }

    public function updatePermission(
        Permission $permission,
        array $data
    ): Permission {

        return DB::transaction(function () use (
            $permission,
            $data
        ) {

            return $this->repository
                ->update(
                    $permission,
                    $data
                );
        });
    }

    public function deletePermission(
        Permission $permission
    ): bool {

        if (
            str_contains(
                $permission->name,
                'super_admin'
            )
        ) {
            throw new Exception(
                'Permiso protegido.'
            );
        }

        return $this->repository
            ->delete($permission);
    }
}