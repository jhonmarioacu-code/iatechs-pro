<?php

declare(strict_types=1);

namespace App\Domains\Companies\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Companies\Models\Company;

class CompanyPolicy
{
    public function viewAny(
        User $user
    ): bool {
        return $user->can('companies.view');
    }

    public function view(
        User $user,
        Company $company
    ): bool {
        return $user->can('companies.view');
    }

    public function create(
        User $user
    ): bool {
        return $user->can('companies.create');
    }

    public function update(
        User $user,
        Company $company
    ): bool {
        return $user->can('companies.update');
    }

    public function delete(
        User $user,
        Company $company
    ): bool {
        return $user->can('companies.delete');
    }

    public function activate(
        User $user,
        Company $company
    ): bool {
        return $user->can('companies.activate');
    }

    public function suspend(
        User $user,
        Company $company
    ): bool {
        return $user->can('companies.suspend');
    }

    public function restore(
        User $user,
        Company $company
    ): bool {
        return $user->can('companies.restore');
    }

    public function forceDelete(
        User $user,
        Company $company
    ): bool {
        return $user->can('companies.force-delete');
    }
}