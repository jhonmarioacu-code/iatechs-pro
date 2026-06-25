<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Companies\Models\Company;
use App\Domains\Dashboard\Models\Dashboard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('slug', 'iatechs-demo')->first();

        if (!$company) {
            return;
        }

        Dashboard::updateOrCreate(
            [
                'company_id' => $company->id,
                'name' => 'Panel principal',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'description' => 'Default operational dashboard',
                'is_default' => true,
            ]
        );
    }
}
