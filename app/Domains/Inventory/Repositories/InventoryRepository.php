<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Inventory\Models\InventoryMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventoryRepository
{
    use ProvidesRepositoryDefaults;

    protected function repositoryModelClass(): string
    {
        return InventoryMovement::class;
    }

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return InventoryMovement::query()
            ->with([
                'product',
                'branch',
                'user'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?InventoryMovement {

        return InventoryMovement::find($id);
    }

    public function create(
        array $data
    ): InventoryMovement {

        return InventoryMovement::create($data);
    }

    public function update(
        InventoryMovement $movement,
        array $data
    ): InventoryMovement {

        $movement->update($data);

        return $movement->refresh();
    }

    public function delete(
        InventoryMovement $movement
    ): bool {

        return (bool) $movement->delete();
    }
}
