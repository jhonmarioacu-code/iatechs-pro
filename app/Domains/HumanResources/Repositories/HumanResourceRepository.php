<?php

declare(strict_types=1);

namespace App\Domains\HumanResources\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\HumanResources\Models\HumanResource;

class HumanResourceRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return HumanResource::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): HumanResource
    {
        return HumanResource::create($data);
    }

    public function update(HumanResource $humanResource, array $data): HumanResource
    {
        $humanResource->update($data);

        return $humanResource->refresh();
    }

    public function delete(HumanResource $humanResource): bool
    {
        return (bool) $humanResource->delete();
    }
}
