<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Domains\Products\Models\Product;
use App\Domains\Companies\Models\Company;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $products = [

            [
                'sku' => 'SERV-001',
                'name' => 'Diagnóstico Técnico'
            ],

            [
                'sku' => 'SERV-002',
                'name' => 'Reparación General'
            ],

            [
                'sku' => 'SERV-003',
                'name' => 'Mantenimiento Preventivo'
            ]
        ];

        foreach ($products as $product) {

            Product::firstOrCreate(

                [
                    'sku' => $product['sku']
                ],

                [
                    'uuid' => Str::uuid(),

                    'company_id' => $company->id,

                    'barcode' => null,

                    'name' => $product['name'],

                    'description' => null,

                    'category' => 'OTHER',

                    'cost_price' => 0,

                    'sale_price' => 0,

                    'stock' => 0,

                    'minimum_stock' => 0,

                    'unit' => 'unit',

                    'status' => 'ACTIVE'
                ]
            );
        }
    }
}