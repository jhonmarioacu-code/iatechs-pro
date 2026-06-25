<?php

declare(strict_types=1);

namespace App\Domains\BusinessIntelligence\Policies;

use App\Domains\BusinessIntelligence\Models\BusinessMetric;
use App\Domains\Users\Models\User;

class BusinessMetricPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('business-intelligence.view');
    }

    public function view(User $user, BusinessMetric $businessMetric): bool
    {
        return $user->can('business-intelligence.view')
            && $user->company_id === $businessMetric->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('business-intelligence.create');
    }

    public function update(User $user, BusinessMetric $businessMetric): bool
    {
        return $user->can('business-intelligence.update')
            && $user->company_id === $businessMetric->company_id;
    }

    public function delete(User $user, BusinessMetric $businessMetric): bool
    {
        return $user->can('business-intelligence.delete')
            && $user->company_id === $businessMetric->company_id;
    }
}
