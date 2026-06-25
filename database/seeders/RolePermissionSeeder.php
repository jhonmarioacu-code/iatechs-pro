<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $rolePermissions = [
            'technician' => [
                'tickets.view',
                'tickets.update',
                'tickets.close',
                'diagnostics.view',
                'diagnostics.create',
                'diagnostics.update',
                'diagnostics.start',
                'diagnostics.complete',
                'diagnostics.cancel',
                'repairs.view',
                'repairs.create',
                'repairs.update',
                'repairs.start',
                'repairs.complete',
                'quotes.view',
                'devices.view',
                'customers.view',
                'work-orders.view',
            ],
            'customer' => [
                'customer.portal.view',
                'customer.portal.tickets.view',
                'customer.portal.invoices.view',
                'customer.portal.pay',
                'customer.portal.marketplace.view',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $resolvedPermissions = collect($permissions)
                ->map(static fn (string $permissionName): Permission => Permission::query()->firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]))
                ->all();

            $role->syncPermissions($resolvedPermissions);
        }
    }
}
