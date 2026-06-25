<?php

declare(strict_types=1);

namespace App\Domains\Suppliers\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Suppliers\Models\Supplier;

class SupplierPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'suppliers.view'
        );
    }

    public function view(
        User $user,
        Supplier $supplier
    ): bool {

        return
            $user->can('suppliers.view')
            &&
            $user->company_id ===
            $supplier->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'suppliers.create'
        );
    }

    public function update(
        User $user,
        Supplier $supplier
    ): bool {

        return
            $user->can('suppliers.update')
            &&
            $user->company_id ===
            $supplier->company_id;
    }

    public function delete(
        User $user,
        Supplier $supplier
    ): bool {

        return
            $user->can('suppliers.delete')
            &&
            $user->company_id ===
            $supplier->company_id;
    }

    public function activate(
        User $user,
        Supplier $supplier
    ): bool {

        return $user->can(
            'suppliers.activate'
        );
    }

    public function deactivate(
        User $user,
        Supplier $supplier
    ): bool {

        return $user->can(
            'suppliers.deactivate'
        );
    }

    public function block(
        User $user,
        Supplier $supplier
    ): bool {

        return $user->can(
            'suppliers.block'
        );
    }
}