<?php

declare(strict_types=1);

namespace App\Domains\TechnicianSchedules\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\TechnicianSchedules\Models\TechnicianSchedule;

class TechnicianScheduleRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return TechnicianSchedule::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): TechnicianSchedule
    {
        return TechnicianSchedule::create($data);
    }

    public function update(TechnicianSchedule $technicianSchedule, array $data): TechnicianSchedule
    {
        $technicianSchedule->update($data);

        return $technicianSchedule->refresh();
    }

    public function delete(TechnicianSchedule $technicianSchedule): bool
    {
        return (bool) $technicianSchedule->delete();
    }
}
