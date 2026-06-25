<?php

declare(strict_types=1);

namespace App\Domains\Compliance\Policies;

use App\Domains\Compliance\Models\ComplianceRecord;
use App\Domains\Users\Models\User;

class ComplianceRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('compliance.view');
    }

    public function view(User $user, ComplianceRecord $complianceRecord): bool
    {
        return $user->can('compliance.view')
            && $user->company_id === $complianceRecord->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('compliance.create');
    }

    public function update(User $user, ComplianceRecord $complianceRecord): bool
    {
        return $user->can('compliance.update')
            && $user->company_id === $complianceRecord->company_id;
    }

    public function delete(User $user, ComplianceRecord $complianceRecord): bool
    {
        return $user->can('compliance.delete')
            && $user->company_id === $complianceRecord->company_id;
    }
}
