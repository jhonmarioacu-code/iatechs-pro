<?php

declare(strict_types=1);

namespace App\Domains\Procurement\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Procurement\Models\Procurement;

class ProcurementRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return Procurement::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Procurement
    {
        return Procurement::create($data);
    }

    public function update(Procurement $procurement, array $data): Procurement
    {
        $procurement->update($data);

        return $procurement->refresh();
    }

    public function delete(Procurement $procurement): bool
    {
        return (bool) $procurement->delete();
    }
}
