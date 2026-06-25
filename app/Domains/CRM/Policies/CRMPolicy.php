<?php

declare(strict_types=1);

namespace App\Domains\CRM\Policies;

use App\Models\User;
use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\Opportunity;

class CRMPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('crm.view');
    }

    public function view(User $user): bool
    {
        return $user->can('crm.view');
    }

    public function create(User $user): bool
    {
        return $user->can('crm.create');
    }

    public function update(User $user): bool
    {
        return $user->can('crm.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('crm.delete');
    }

    public function convert(User $user, Lead $lead): bool
    {
        return $user->can('crm.convert');
    }

    public function win(User $user, Opportunity $opportunity): bool
    {
        return $user->can('crm.win');
    }

    public function lose(User $user, Opportunity $opportunity): bool
    {
        return $user->can('crm.lose');
    }
}