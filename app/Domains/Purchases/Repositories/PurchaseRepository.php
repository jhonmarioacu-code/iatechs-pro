<?php

declare(strict_types=1);

namespace App\Domains\Purchases\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Purchases\Models\Purchase;

class PurchaseRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return Purchase::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Purchase
    {
        return Purchase::create($data);
    }

    public function update(Purchase $purchase, array $data): Purchase
    {
        $purchase->update($data);

        return $purchase->refresh();
    }

    public function delete(Purchase $purchase): bool
    {
        return (bool) $purchase->delete();
    }
}
