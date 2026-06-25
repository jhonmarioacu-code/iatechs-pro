<?php

declare(strict_types=1);

namespace App\Domains\Warranties\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Warranties\Models\Warranty;

class WarrantyPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'warranties.view'
        );
    }

    public function view(
        User $user,
        Warranty $warranty
    ): bool {

        return
            $user->can('warranties.view')
            &&
            $user->company_id ===
            $warranty->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'warranties.create'
        );
    }

    public function update(
        User $user,
        Warranty $warranty
    ): bool {

        return
            $user->can('warranties.update')
            &&
            $user->company_id ===
            $warranty->company_id;
    }

    public function delete(
        User $user,
        Warranty $warranty
    ): bool {

        return
            $user->can('warranties.delete')
            &&
            $user->company_id ===
            $warranty->company_id;
    }

    public function claim(
        User $user,
        Warranty $warranty
    ): bool {

        return
            $user->can('warranties.claim')
            &&
            $user->company_id ===
            $warranty->company_id;
    }

    public function expire(
        User $user,
        Warranty $warranty
    ): bool {

        return
            $user->can('warranties.expire')
            &&
            $user->company_id ===
            $warranty->company_id;
    }

    public function void(
        User $user,
        Warranty $warranty
    ): bool {

        return
            $user->can('warranties.void')
            &&
            $user->company_id ===
            $warranty->company_id;
    }
}