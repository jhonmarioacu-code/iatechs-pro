<?php

declare(strict_types=1);

namespace App\Domains\Reports\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Reports\Models\Report;

class ReportPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'reports.view'
        );
    }

    public function view(
        User $user,
        Report $report
    ): bool {

        return $user->company_id ===
            $report->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'reports.create'
        );
    }

    public function export(
        User $user
    ): bool {

        return $user->can(
            'reports.export'
        );
    }

    public function delete(
        User $user
    ): bool {

        return $user->can(
            'reports.delete'
        );
    }
}