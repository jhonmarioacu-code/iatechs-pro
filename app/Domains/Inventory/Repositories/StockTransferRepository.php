<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Inventory\Models\StockTransfer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockTransferRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return StockTransfer::query()
            ->with([
                'product',
                'fromBranch',
                'toBranch'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): StockTransfer {

        return StockTransfer::create($data);
    }

    public function update(
        StockTransfer $transfer,
        array $data
    ): StockTransfer {

        $transfer->update($data);

        return $transfer->refresh();
    }
}