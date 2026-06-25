<?php

declare(strict_types=1);

namespace App\Domains\TechnicianSchedules\Policies;

use App\Domains\TechnicianSchedules\Models\TechnicianSchedule;
use App\Domains\Users\Models\User;

class TechnicianSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('technician-schedules.view');
    }

    public function view(User $user, TechnicianSchedule $technicianSchedule): bool
    {
        return $user->can('technician-schedules.view')
            && $user->company_id === $technicianSchedule->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('technician-schedules.create');
    }

    public function update(User $user, TechnicianSchedule $technicianSchedule): bool
    {
        return $user->can('technician-schedules.update')
            && $user->company_id === $technicianSchedule->company_id;
    }

    public function delete(User $user, TechnicianSchedule $technicianSchedule): bool
    {
        return $user->can('technician-schedules.delete')
            && $user->company_id === $technicianSchedule->company_id;
    }
}
