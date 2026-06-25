<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Dashboard\Models\Dashboard;

class DashboardPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'dashboards.view'
        );
    }

    public function view(
        User $user,
        Dashboard $dashboard
    ): bool {

        return $user->company_id ===
            $dashboard->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'dashboards.create'
        );
    }

    public function update(
        User $user
    ): bool {

        return $user->can(
            'dashboards.update'
        );
    }

    public function delete(
        User $user
    ): bool {

        return $user->can(
            'dashboards.delete'
        );
    }
}
