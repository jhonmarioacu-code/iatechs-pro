<?php

declare(strict_types=1);

namespace App\Domains\Branches\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Branches\Models\Branch;

class BranchPolicy
{
    /**
     * Listar sucursales
     */
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'branches.view'
        );
    }

    /**
     * Ver sucursal
     */
    public function view(
        User $user,
        Branch $branch
    ): bool {

        return
            $user->can('branches.view')
            &&
            $user->company_id ===
            $branch->company_id;
    }

    /**
     * Crear sucursal
     */
    public function create(
        User $user
    ): bool {

        return $user->can(
            'branches.create'
        );
    }

    /**
     * Actualizar sucursal
     */
    public function update(
        User $user,
        Branch $branch
    ): bool {

        return
            $user->can('branches.update')
            &&
            $user->company_id ===
            $branch->company_id;
    }

    /**
     * Eliminar sucursal
     */
    public function delete(
        User $user,
        Branch $branch
    ): bool {

        return
            $user->can('branches.delete')
            &&
            $user->company_id ===
            $branch->company_id;
    }

    /**
     * Restaurar sucursal
     */
    public function restore(
        User $user,
        Branch $branch
    ): bool {

        return
            $user->can('branches.update')
            &&
            $user->company_id ===
            $branch->company_id;
    }

    /**
     * Eliminación permanente
     */
    public function forceDelete(
        User $user,
        Branch $branch
    ): bool {

        return false;
    }

    /**
     * Activar sucursal
     */
    public function activate(
        User $user,
        Branch $branch
    ): bool {

        return
            $user->can('branches.update')
            &&
            $user->company_id ===
            $branch->company_id;
    }

    /**
     * Desactivar sucursal
     */
    public function deactivate(
        User $user,
        Branch $branch
    ): bool {

        return
            $user->can('branches.update')
            &&
            $user->company_id ===
            $branch->company_id;
    }
}