<?php

declare(strict_types=1);

namespace App\Domains\Warranties\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Warranties\Models\Warranty;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WarrantyRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Warranty::query()
            ->with([
                'customer',
                'device',
                'repair',
                'invoice'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Warranty {

        return Warranty::find($id);
    }

    public function create(
        array $data
    ): Warranty {

        return Warranty::create($data);
    }

    public function update(
        Warranty $warranty,
        array $data
    ): Warranty {

        $warranty->update($data);

        return $warranty->refresh();
    }

    public function delete(
        Warranty $warranty
    ): bool {

        return (bool) $warranty->delete();
    }

    public function existsWarrantyNumber(
        string $number
    ): bool {

        return Warranty::query()
            ->where(
                'warranty_number',
                $number
            )
            ->exists();
    }
}