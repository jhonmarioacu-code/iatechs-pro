<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Repairs\Models\Repair;

class RepairPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'repairs.view'
        );
    }

    public function view(
        User $user,
        Repair $repair
    ): bool {

        return
            $user->can('repairs.view')
            &&
            $user->company_id ===
            $repair->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'repairs.create'
        );
    }

    public function update(
        User $user,
        Repair $repair
    ): bool {

        return
            $user->can('repairs.update')
            &&
            $user->company_id ===
            $repair->company_id;
    }

    public function delete(
        User $user,
        Repair $repair
    ): bool {

        return
            $user->can('repairs.delete')
            &&
            $user->company_id ===
            $repair->company_id;
    }

    public function assign(
        User $user,
        Repair $repair
    ): bool {

        return
            $user->can('repairs.assign')
            &&
            $user->company_id ===
            $repair->company_id;
    }

    public function start(
        User $user,
        Repair $repair
    ): bool {

        return
            $user->can('repairs.start')
            &&
            $user->company_id ===
            $repair->company_id;
    }

    public function complete(
        User $user,
        Repair $repair
    ): bool {

        return
            $user->can('repairs.complete')
            &&
            $user->company_id ===
            $repair->company_id;
    }

    public function deliver(
        User $user,
        Repair $repair
    ): bool {

        return
            $user->can('repairs.deliver')
            &&
            $user->company_id ===
            $repair->company_id;
    }

    public function cancel(
        User $user,
        Repair $repair
    ): bool {

        return
            $user->can('repairs.cancel')
            &&
            $user->company_id ===
            $repair->company_id;
    }
}