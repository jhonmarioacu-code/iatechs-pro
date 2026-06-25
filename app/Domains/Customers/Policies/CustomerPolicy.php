<?php

declare(strict_types=1);

namespace App\Domains\Customers\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Customers\Models\Customer;

class CustomerPolicy
{
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function isSuperAdmin(
        User $user
    ): bool {

        return $user->hasRole(
            'super_admin'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | View Any
    |--------------------------------------------------------------------------
    */

    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'customers.view'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

    public function view(
        User $user,
        Customer $customer
    ): bool {

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return
            $user->can('customers.view')
            &&
            $user->company_id ===
            $customer->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        User $user
    ): bool {

        return $user->can(
            'customers.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        User $user,
        Customer $customer
    ): bool {

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return
            $user->can('customers.update')
            &&
            $user->company_id ===
            $customer->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        User $user,
        Customer $customer
    ): bool {

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return
            $user->can('customers.delete')
            &&
            $user->company_id ===
            $customer->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    public function restore(
        User $user,
        Customer $customer
    ): bool {

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return
            $user->can('customers.update')
            &&
            $user->company_id ===
            $customer->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Activate
    |--------------------------------------------------------------------------
    */

    public function activate(
        User $user,
        Customer $customer
    ): bool {

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return
            $user->can('customers.update')
            &&
            $user->company_id ===
            $customer->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Deactivate
    |--------------------------------------------------------------------------
    */

    public function deactivate(
        User $user,
        Customer $customer
    ): bool {

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return
            $user->can('customers.update')
            &&
            $user->company_id ===
            $customer->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    */

    public function export(
        User $user
    ): bool {

        return $user->can(
            'customers.export'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Force Delete
    |--------------------------------------------------------------------------
    */

    public function forceDelete(
        User $user,
        Customer $customer
    ): bool {

        return false;
    }
}