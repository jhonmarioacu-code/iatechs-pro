<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Domains\Accounting\Models\FiscalPeriod;
use App\Domains\Companies\Models\Company;

class FiscalPeriodSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        FiscalPeriod::firstOrCreate(

            [
                'name' => '2026'
            ],

            [
                'uuid' => Str::uuid(),

                'company_id' => $company->id,

                'start_date' => '2026-01-01',

                'end_date' => '2026-12-31',

                'is_closed' => false,

                'closed_at' => null
            ]
        );
    }
}