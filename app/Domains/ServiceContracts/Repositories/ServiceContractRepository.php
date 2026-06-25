<?php

declare(strict_types=1);

namespace App\Domains\ServiceContracts\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\ServiceContracts\Models\ServiceContract;

class ServiceContractRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return ServiceContract::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): ServiceContract
    {
        return ServiceContract::create($data);
    }

    public function update(ServiceContract $serviceContract, array $data): ServiceContract
    {
        $serviceContract->update($data);

        return $serviceContract->refresh();
    }

    public function delete(ServiceContract $serviceContract): bool
    {
        return (bool) $serviceContract->delete();
    }
}
