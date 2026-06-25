<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Domains\Users\Models\User;
use App\Domains\Companies\Models\Company;
use Spatie\Permission\Models\Role;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            SuperAdminPermissionSeeder::class,
        ]);

        $adminEmail = (string) config('production.admin_email', '');
        $adminPassword = (string) config('production.admin_password', '');
        $adminName = (string) config('production.admin_name', 'IAtechs Super Admin');

        if ($adminEmail === '' || $adminPassword === '') {
            $this->command->warn(
                'ProductionSeeder: no se creó usuario admin porque faltan PRODUCTION_ADMIN_EMAIL/PRODUCTION_ADMIN_PASSWORD.'
            );

            return;
        }

        $company = Company::query()->first();

        if (!$company) {
            $company = Company::create([
                'uuid' => (string) Str::uuid(),
                'name' => 'IAtechs Production',
                'slug' => 'iatechs-production',
                'legal_name' => 'IAtechs Production S.A.S.',
                'tax_id' => '900000000-0',
                'email' => $adminEmail,
                'phone' => null,
                'website' => null,
                'address' => null,
                'city' => null,
                'country' => 'Colombia',
                'logo' => null,
                'status' => Company::STATUS_ACTIVE,
                'trial_ends_at' => now()->addDays(7),
            ]);
        }

        $user = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'company_id' => $company->id,
                'uuid' => (string) Str::uuid(),
                'name' => $adminName,
                'password' => $adminPassword,
                'phone' => null,
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $role = Role::query()
            ->where('name', 'super_admin')
            ->where('guard_name', 'web')
            ->first();

        if ($role) {
            $user->syncRoles([$role]);
        }
    }
}
