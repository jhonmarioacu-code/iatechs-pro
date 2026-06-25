<?php

declare(strict_types=1);

namespace App\Domains\HumanResources\Services;

use App\Domains\HumanResources\Enums\HumanResourceStatus;
use App\Domains\HumanResources\Models\HumanResource;
use App\Domains\HumanResources\Repositories\HumanResourceRepository;
use Illuminate\Support\Str;

class HumanResourceService
{
    public function __construct(
        private HumanResourceRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): HumanResource
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? HumanResourceStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(HumanResource $humanResource, array $data): HumanResource
    {
        return $this->repository->update($humanResource, $data);
    }

    public function delete(HumanResource $humanResource): bool
    {
        return $this->repository->delete($humanResource);
    }
}
