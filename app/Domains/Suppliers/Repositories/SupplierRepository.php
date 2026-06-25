<?php

declare(strict_types=1);

namespace App\Domains\Suppliers\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Suppliers\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupplierRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Supplier::query()
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Supplier {

        return Supplier::find($id);
    }

    public function create(
        array $data
    ): Supplier {

        return Supplier::create($data);
    }

    public function update(
        Supplier $supplier,
        array $data
    ): Supplier {

        $supplier->update($data);

        return $supplier->refresh();
    }

    public function delete(
        Supplier $supplier
    ): bool {

        return (bool) $supplier->delete();
    }

    public function existsTaxId(
        string $taxId
    ): bool {

        return Supplier::query()
            ->where(
                'tax_id',
                $taxId
            )
            ->exists();
    }
}