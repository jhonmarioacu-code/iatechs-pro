<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Domains\Accounting\Models\Account;
use App\Domains\Companies\Models\Company;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $accounts = [

            ['1000', 'Caja', 'asset'],
            ['1100', 'Bancos', 'asset'],
            ['1200', 'Clientes', 'asset'],

            ['2000', 'Proveedores', 'liability'],

            ['3000', 'Capital', 'equity'],

            ['4000', 'Ingresos', 'income'],

            ['5000', 'Gastos', 'expense']
        ];

        foreach ($accounts as $account) {

            Account::firstOrCreate(

                [
                    'code' => $account[0],
                    'company_id' => $company->id
                ],

                [
                    'uuid' => Str::uuid(),

                    'name' => $account[1],

                    'type' => $account[2],

                    'parent_id' => null,

                    'is_active' => true
                ]
            );
        }
    }
}