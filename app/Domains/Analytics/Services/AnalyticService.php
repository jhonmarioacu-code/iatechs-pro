<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Services;

use App\Domains\Analytics\Enums\AnalyticStatus;
use App\Domains\Analytics\Models\Analytic;
use App\Domains\Analytics\Repositories\AnalyticRepository;
use Illuminate\Support\Str;

class AnalyticService
{
    public function __construct(
        private AnalyticRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): Analytic
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? AnalyticStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(Analytic $analytic, array $data): Analytic
    {
        return $this->repository->update($analytic, $data);
    }

    public function delete(Analytic $analytic): bool
    {
        return $this->repository->delete($analytic);
    }
}
