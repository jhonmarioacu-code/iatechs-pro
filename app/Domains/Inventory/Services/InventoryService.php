<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Products\Models\Product;
use App\Domains\Inventory\Models\InventoryMovement;
use App\Domains\Inventory\Repositories\InventoryRepository;

class InventoryService
{
    public function __construct(
        private InventoryRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): InventoryMovement {

        return DB::transaction(function () use ($data) {

            $product = Product::findOrFail(
                $data['product_id']
            );

            $stockBefore =
                $product->stock;

            $stockAfter =
                $this->calculateStock(
                    $product,
                    $data['type'],
                    $data['quantity']
                );

            $product->update([
                'stock' => $stockAfter
            ]);

            $data['uuid'] =
                (string) Str::uuid();

            $data['stock_before'] =
                $stockBefore;

            $data['stock_after'] =
                $stockAfter;

            return $this->repository
                ->create($data);
        });
    }

    private function calculateStock(
        Product $product,
        string $type,
        int $quantity
    ): int {

        return match ($type) {

            'IN',
            'TRANSFER_IN'
                => $product->stock + $quantity,

            'OUT',
            'TRANSFER_OUT'
                => max(
                    0,
                    $product->stock - $quantity
                ),

            'ADJUSTMENT'
                => $quantity,

            default
                => $product->stock
        };
    }
}