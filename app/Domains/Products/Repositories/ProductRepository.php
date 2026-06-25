<?php

declare(strict_types=1);

namespace App\Domains\Products\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Products\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Product::query()
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Product {

        return Product::find($id);
    }

    public function create(
        array $data
    ): Product {

        return Product::create($data);
    }

    public function update(
        Product $product,
        array $data
    ): Product {

        $product->update($data);

        return $product->refresh();
    }

    public function delete(
        Product $product
    ): bool {

        return (bool) $product->delete();
    }

    public function existsSku(
        string $sku
    ): bool {

        return Product::query()
            ->where('sku', $sku)
            ->exists();
    }

    public function lowStock()
    {
        return Product::query()
            ->whereColumn(
                'stock',
                '<=',
                'minimum_stock'
            )
            ->get();
    }
}