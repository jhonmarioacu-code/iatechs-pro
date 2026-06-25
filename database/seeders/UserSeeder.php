<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Companies\Models\Company;
use App\Domains\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('slug', 'iatechs-demo')->first();

        if (!$company) {
            return;
        }

        $user = User::updateOrCreate(
            ['email' => 'admin@iatechs.test'],
            [
                'company_id' => $company->id,
                'uuid' => (string) Str::uuid(),
                'name' => 'IAtechs Admin',
                'password' => 'password',
                'phone' => '+57 300 000 0000',
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('super_admin');
    }
}
