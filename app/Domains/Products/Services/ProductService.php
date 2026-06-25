<?php

declare(strict_types=1);

namespace App\Domains\Products\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Products\Models\Product;
use App\Domains\Products\Repositories\ProductRepository;

class ProductService
{
    public function __construct(
        private ProductRepository $repository
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
    ): Product {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            $data['sku'] =
                $data['sku']
                ??
                $this->generateSku();

            $data['status'] =
                $data['status']
                ??
                'ACTIVE';

            return $this->repository
                ->create($data);
        });
    }

    public function update(
        Product $product,
        array $data
    ): Product {

        return $this->repository
            ->update(
                $product,
                $data
            );
    }

    public function activate(
        Product $product
    ): Product {

        return $this->repository
            ->update(
                $product,
                [
                    'status' => 'ACTIVE'
                ]
            );
    }

    public function deactivate(
        Product $product
    ): Product {

        return $this->repository
            ->update(
                $product,
                [
                    'status' => 'INACTIVE'
                ]
            );
    }

    public function adjustStock(
        Product $product,
        int $quantity
    ): Product {

        return $this->repository
            ->update(
                $product,
                [
                    'stock' =>
                        $product->stock + $quantity
                ]
            );
    }

    private function generateSku(): string
    {
        do {

            $sku =
                'PRD-' .
                strtoupper(
                    Str::random(10)
                );

        } while (
            $this->repository
                ->existsSku($sku)
        );

        return $sku;
    }
}