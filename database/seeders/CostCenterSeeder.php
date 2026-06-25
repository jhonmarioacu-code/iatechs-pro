<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Domains\Accounting\Models\CostCenter;
use App\Domains\Companies\Models\Company;

class CostCenterSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $centers = [

            ['ADM', 'Administración'],
            ['SAL', 'Ventas'],
            ['SUP', 'Soporte'],
            ['TAL', 'Taller'],
            ['INV', 'Inventario']
        ];

        foreach ($centers as $center) {

            CostCenter::firstOrCreate(

                [
                    'code' => $center[0],
                    'company_id' => $company->id
                ],

                [
                    'uuid' => Str::uuid(),

                    'name' => $center[1],

                    'description' => null,

                    'is_active' => true
                ]
            );
        }
    }
}