<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Repairs\Models\Repair;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RepairRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Repair::query()
            ->with([
                'ticket',
                'diagnostic',
                'quote',
                'technician'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Repair {

        return Repair::find($id);
    }

    public function create(
        array $data
    ): Repair {

        return Repair::create($data);
    }

    public function update(
        Repair $repair,
        array $data
    ): Repair {

        $repair->update($data);

        return $repair->refresh();
    }

    public function delete(
        Repair $repair
    ): bool {

        return (bool) $repair->delete();
    }

    public function existsRepairNumber(
        string $number
    ): bool {

        return Repair::query()
            ->where(
                'repair_number',
                $number
            )
            ->exists();
    }
}