<?php

declare(strict_types=1);

namespace App\Domains\Purchases\Services;

use App\Domains\Purchases\Enums\PurchaseStatus;
use App\Domains\Purchases\Models\Purchase;
use App\Domains\Purchases\Repositories\PurchaseRepository;
use Illuminate\Support\Str;

class PurchaseService
{
    public function __construct(
        private PurchaseRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): Purchase
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? PurchaseStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(Purchase $purchase, array $data): Purchase
    {
        return $this->repository->update($purchase, $data);
    }

    public function delete(Purchase $purchase): bool
    {
        return $this->repository->delete($purchase);
    }
}
