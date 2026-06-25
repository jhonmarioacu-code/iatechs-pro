<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Plans\Models\Plan;
use App\Domains\Users\Models\User;
use App\Domains\Companies\Models\Company;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

function createCompanyForEnterprise(string $name, string $slug): Company
{
    return Company::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => $name,
        'slug' => $slug,
        'status' => Company::STATUS_ACTIVE,
        'country' => 'CO',
    ]);
}

function createUserForEnterprise(
    Company $company,
    string $email,
    string $roleName,
    bool $isActive = true
): User {
    $user = User::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'name' => 'Enterprise '.$roleName,
        'email' => $email,
        'password' => 'Secret123!',
        'is_active' => $isActive,
        'email_verified_at' => now(),
    ]);

    $role = Role::query()->firstOrCreate([
        'name' => $roleName,
        'guard_name' => 'web',
    ]);

    $user->assignRole($role);

    return $user;
}

it('restricts observability dashboard to super admin users', function (): void {
    $company = createCompanyForEnterprise('Ops Co', 'ops-co');
    $owner = createUserForEnterprise(
        $company,
        'owner-ops@example.com',
        'owner'
    );

    $this->actingAs($owner)
        ->get('/admin/observability')
        ->assertForbidden();

    $superAdmin = createUserForEnterprise(
        $company,
        'admin-ops@example.com',
        'super_admin'
    );

    $this->actingAs($superAdmin)
        ->get('/admin/observability')
        ->assertOk();
});

it('updates account password only when current password is valid', function (): void {
    $company = createCompanyForEnterprise('Security Co', 'security-co');
    $owner = createUserForEnterprise(
        $company,
        'owner-security@example.com',
        'owner'
    );

    $this->actingAs($owner)
        ->post('/portal/account/security', [
            'current_password' => 'bad-password',
            'password' => 'NewSecure123',
            'password_confirmation' => 'NewSecure123',
        ])
        ->assertSessionHasErrors('current_password');

    $this->assertTrue(Hash::check('Secret123!', (string) $owner->fresh()->password));

    $this->actingAs($owner)
        ->post('/portal/account/security', [
            'current_password' => 'Secret123!',
            'password' => 'NewSecure123',
            'password_confirmation' => 'NewSecure123',
        ])
        ->assertRedirect(route('portal.account.security.edit'));

    $this->assertTrue(Hash::check('NewSecure123', (string) $owner->fresh()->password));
});

it('allows super admin to create company user and subscription from operations module', function (): void {
    $hostCompany = createCompanyForEnterprise('Host Co', 'host-co');
    $superAdmin = createUserForEnterprise(
        $hostCompany,
        'super-admin@example.com',
        'super_admin'
    );

    $ownerRole = Role::query()->firstOrCreate([
        'name' => 'owner',
        'guard_name' => 'web',
    ]);

    $plan = Plan::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Growth',
        'slug' => 'growth',
        'monthly_price' => 79,
        'yearly_price' => 790,
        'status' => 'active',
    ]);

    $this->actingAs($superAdmin)
        ->post('/portal/admin/operations/company', [
            'name' => 'Created By Operations',
            'email' => 'ops-company@example.com',
            'city' => 'Bogota',
            'country' => 'CO',
        ])
        ->assertRedirect(route('portal.admin.operations'));

    $createdCompany = Company::query()
        ->where('name', 'Created By Operations')
        ->first();

    expect($createdCompany)->not()->toBeNull();

    $this->actingAs($superAdmin)
        ->post('/portal/admin/operations/user', [
            'company_id' => $createdCompany?->id,
            'name' => 'Ops Owner',
            'email' => 'ops-owner@example.com',
            'password' => 'Secure1234',
            'role' => $ownerRole->name,
            'is_active' => 1,
        ])
        ->assertRedirect(route('portal.admin.operations'));

    $createdUser = User::query()
        ->where('email', 'ops-owner@example.com')
        ->first();

    expect($createdUser)->not()->toBeNull();
    expect($createdUser?->hasRole('owner'))->toBeTrue();

    $this->actingAs($superAdmin)
        ->post('/portal/admin/operations/subscription', [
            'company_id' => $createdCompany?->id,
            'plan_id' => $plan->id,
            'billing_cycle' => 'monthly',
            'starts_at' => now()->toDateString(),
            'ends_at' => now()->addMonth()->toDateString(),
        ])
        ->assertRedirect(route('portal.admin.operations'));

    $subscriptionCount = DB::table('subscriptions')
        ->where('company_id', $createdCompany?->id)
        ->count();

    expect($subscriptionCount)->toBeGreaterThan(0);
});
