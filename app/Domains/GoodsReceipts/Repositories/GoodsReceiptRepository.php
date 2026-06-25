<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\GoodsReceipts\Models\GoodsReceipt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GoodsReceiptRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return GoodsReceipt::query()
            ->with([
                'supplier',
                'purchaseOrder',
                'items.product'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): GoodsReceipt {

        return GoodsReceipt::create($data);
    }

    public function update(
        GoodsReceipt $receipt,
        array $data
    ): GoodsReceipt {

        $receipt->update($data);

        return $receipt->refresh();
    }

    public function find(
        int $id
    ): ?GoodsReceipt {

        return GoodsReceipt::find($id);
    }
}