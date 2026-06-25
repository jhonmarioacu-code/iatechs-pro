<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Domains\Suppliers\Models\Supplier;
use App\Domains\Companies\Models\Company;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        Supplier::firstOrCreate(

            [
                'name' => 'Proveedor General'
            ],

            [
                'uuid' => Str::uuid(),

                'company_id' => $company->id,

                'legal_name' => 'Proveedor General SAS',

                'tax_id' => '900000002',

                'email' => 'proveedor@iatechspro.com',

                'phone' => '3000000001',

                'website' => null,

                'contact_name' => 'Proveedor Principal',

                'address' => 'Soledad',

                'city' => 'Soledad',

                'state' => 'Atlántico',

                'country' => 'Colombia',

                'status' => 'ACTIVE',

                'metadata' => null
            ]
        );
    }
}