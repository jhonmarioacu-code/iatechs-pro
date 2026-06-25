<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Policies;

use App\Domains\Analytics\Models\Analytic;
use App\Domains\Users\Models\User;

class AnalyticPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('analytics.view');
    }

    public function view(User $user, Analytic $analytic): bool
    {
        return $user->can('analytics.view')
            && $user->company_id === $analytic->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('analytics.create');
    }

    public function update(User $user, Analytic $analytic): bool
    {
        return $user->can('analytics.update')
            && $user->company_id === $analytic->company_id;
    }

    public function delete(User $user, Analytic $analytic): bool
    {
        return $user->can('analytics.delete')
            && $user->company_id === $analytic->company_id;
    }
}
