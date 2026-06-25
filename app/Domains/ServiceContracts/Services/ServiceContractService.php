<?php

declare(strict_types=1);

namespace App\Domains\ServiceContracts\Services;

use App\Domains\ServiceContracts\Enums\ServiceContractStatus;
use App\Domains\ServiceContracts\Models\ServiceContract;
use App\Domains\ServiceContracts\Repositories\ServiceContractRepository;
use Illuminate\Support\Str;

class ServiceContractService
{
    public function __construct(
        private ServiceContractRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): ServiceContract
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? ServiceContractStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(ServiceContract $serviceContract, array $data): ServiceContract
    {
        return $this->repository->update($serviceContract, $data);
    }

    public function delete(ServiceContract $serviceContract): bool
    {
        return $this->repository->delete($serviceContract);
    }
}
