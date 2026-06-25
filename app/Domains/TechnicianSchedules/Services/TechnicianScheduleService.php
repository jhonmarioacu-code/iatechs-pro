<?php

declare(strict_types=1);

namespace App\Domains\TechnicianSchedules\Services;

use App\Domains\TechnicianSchedules\Enums\TechnicianScheduleStatus;
use App\Domains\TechnicianSchedules\Models\TechnicianSchedule;
use App\Domains\TechnicianSchedules\Repositories\TechnicianScheduleRepository;
use Illuminate\Support\Str;

class TechnicianScheduleService
{
    public function __construct(
        private TechnicianScheduleRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): TechnicianSchedule
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? TechnicianScheduleStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(TechnicianSchedule $technicianSchedule, array $data): TechnicianSchedule
    {
        return $this->repository->update($technicianSchedule, $data);
    }

    public function delete(TechnicianSchedule $technicianSchedule): bool
    {
        return $this->repository->delete($technicianSchedule);
    }
}
