<?php

declare(strict_types=1);

use App\Domains\Plans\Models\Plan;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('allows super admin to update company, user, subscription and technician permissions', function (): void {
    $hostCompany = sec_create_company('Host Admin Co', 'host-admin-co');
    $superAdmin = sec_create_user(
        $hostCompany,
        'host-super-admin@example.com',
        'super_admin'
    );

    $targetCompany = sec_create_company('Managed Co', 'managed-co');
    $plan = sec_create_plan('managed-plan', 99, 999);

    $technician = sec_create_user(
        $targetCompany,
        'managed-tech@example.com',
        'technician',
        ['tickets.view']
    );

    $subscription = Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $targetCompany->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 99,
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addMonth()->toDateString(),
        'status' => 'active',
    ]);

    $this->actingAs($superAdmin)
        ->put(route('portal.admin.operations.company.update', $targetCompany), [
            'name' => 'Managed Co Updated',
            'legal_name' => 'Managed Company LLC',
            'tax_id' => 'TAX-001',
            'email' => 'managed@example.com',
            'phone' => '3000000001',
            'city' => 'Bogota',
            'country' => 'CO',
            'status' => 'active',
        ])
        ->assertRedirect(route('portal.admin.operations'));

    $ownerRole = \Spatie\Permission\Models\Role::query()->firstOrCreate([
        'name' => 'owner',
        'guard_name' => 'web',
    ]);

    $this->actingAs($superAdmin)
        ->put(route('portal.admin.operations.user.update', $technician), [
            'company_id' => $targetCompany->id,
            'name' => 'Managed Owner',
            'email' => 'managed-owner@example.com',
            'phone' => '3000000002',
            'role' => $ownerRole->name,
            'is_active' => 1,
        ])
        ->assertRedirect(route('portal.admin.operations'));

    $this->actingAs($superAdmin)
        ->put(route('portal.admin.operations.subscription.update', $subscription), [
            'company_id' => $targetCompany->id,
            'plan_id' => $plan->id,
            'billing_cycle' => 'yearly',
            'status' => 'active',
            'amount' => 999,
            'starts_at' => now()->toDateString(),
            'ends_at' => now()->addYear()->toDateString(),
        ])
        ->assertRedirect(route('portal.admin.operations'));

    Permission::query()->firstOrCreate(['name' => 'diagnostics.create', 'guard_name' => 'web']);
    Permission::query()->firstOrCreate(['name' => 'quotes.create', 'guard_name' => 'web']);

    $technicianForPermissions = User::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $targetCompany->id,
        'name' => 'Managed Technician',
        'email' => 'managed-technician@example.com',
        'password' => 'Secret123!',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    $technicianForPermissions->assignRole('technician');

    $this->actingAs($superAdmin)
        ->post(route('portal.admin.operations.user.permissions.sync', $technicianForPermissions), [
            'permissions' => ['diagnostics.create', 'quotes.create'],
        ])
        ->assertRedirect(route('portal.admin.operations'));

    expect($targetCompany->fresh()->name)->toBe('Managed Co Updated');

    $updatedUser = $technician->fresh();
    expect($updatedUser?->email)->toBe('managed-owner@example.com');
    expect($updatedUser?->hasRole('owner'))->toBeTrue();

    $updatedSubscription = $subscription->fresh();
    expect($updatedSubscription?->billing_cycle)->toBe('yearly');
    expect((float) $updatedSubscription?->amount)->toBe(999.0);

    expect($technicianForPermissions->fresh()->can('diagnostics.create'))->toBeTrue();
    expect($technicianForPermissions->fresh()->can('quotes.create'))->toBeTrue();
});
