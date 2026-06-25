<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Companies\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::updateOrCreate(
            ['slug' => 'iatechs-demo'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'IAtechs Demo',
                'legal_name' => 'IAtechs Demo S.A.S.',
                'tax_id' => '900000000-1',
                'email' => 'admin@iatechs.test',
                'phone' => '+57 300 000 0000',
                'website' => 'https://iatechs.test',
                'address' => 'Avenida Principal 123',
                'city' => 'Bogota',
                'country' => 'Colombia',
                'logo' => null,
                'status' => Company::STATUS_ACTIVE,
                'trial_ends_at' => now()->addDays(30),
            ]
        );
    }
}
