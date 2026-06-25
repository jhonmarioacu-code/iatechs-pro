<?php

declare(strict_types=1);

namespace App\Domains\Procurement\Services;

use App\Domains\Procurement\Enums\ProcurementStatus;
use App\Domains\Procurement\Models\Procurement;
use App\Domains\Procurement\Repositories\ProcurementRepository;
use Illuminate\Support\Str;

class ProcurementService
{
    public function __construct(
        private ProcurementRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): Procurement
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? ProcurementStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(Procurement $procurement, array $data): Procurement
    {
        return $this->repository->update($procurement, $data);
    }

    public function delete(Procurement $procurement): bool
    {
        return $this->repository->delete($procurement);
    }
}
