<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('enforces auth and permission on critical admin apis', function (): void {
    $this->getJson('/api/v1/companies')->assertStatus(401);
    $this->getJson('/api/v1/plans')->assertStatus(401);
    $this->getJson('/api/v1/subscriptions')->assertStatus(401);

    $company = sec_create_company('Admin API Co', 'admin-api-co');
    $userWithoutPermissions = sec_create_user(
        $company,
        'admin-api-no-perm@example.com',
        'owner'
    );

    $this->actingAs($userWithoutPermissions, 'sanctum')
        ->getJson('/api/v1/companies')
        ->assertStatus(403);

    $this->actingAs($userWithoutPermissions, 'sanctum')
        ->getJson('/api/v1/plans')
        ->assertStatus(403);

    $this->actingAs($userWithoutPermissions, 'sanctum')
        ->getJson('/api/v1/subscriptions')
        ->assertStatus(403);
});

it('returns validation errors for critical admin api write endpoints', function (): void {
    $company = sec_create_company('Admin API Write Co', 'admin-api-write-co');
    $writer = sec_create_user(
        $company,
        'admin-api-writer@example.com',
        'owner',
        ['companies.create', 'plans.create', 'subscriptions.create']
    );

    $this->actingAs($writer, 'sanctum')
        ->postJson('/api/v1/companies', [])
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);

    $this->actingAs($writer, 'sanctum')
        ->postJson('/api/v1/plans', [])
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);

    $this->actingAs($writer, 'sanctum')
        ->postJson('/api/v1/subscriptions', [])
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);
});

it('enforces full negative coverage for admin operations routes', function (): void {
    $company = sec_create_company('Ops Negative Co', 'ops-negative-co');
    $owner = sec_create_user(
        $company,
        'owner-ops-negative@example.com',
        'owner'
    );
    $superAdmin = sec_create_user(
        $company,
        'super-ops-negative@example.com',
        'super_admin'
    );
    $plan = sec_create_plan('ops-negative-plan');

    $this->get('/portal/admin/operations')
        ->assertRedirect(route('login'));

    $this->actingAs($owner)
        ->get('/portal/admin/operations')
        ->assertForbidden();

    $this->actingAs($owner)
        ->post('/portal/admin/operations/company', ['name' => 'Forbidden Co'])
        ->assertForbidden();

    $this->actingAs($owner)
        ->post('/portal/admin/operations/user', [
            'company_id' => $company->id,
            'name' => 'Forbidden User',
            'email' => 'forbidden@example.com',
            'password' => 'Secure1234',
            'role' => 'owner',
        ])
        ->assertForbidden();

    $this->actingAs($owner)
        ->post('/portal/admin/operations/subscription', [
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'billing_cycle' => 'monthly',
        ])
        ->assertForbidden();

    $this->actingAs($superAdmin)
        ->post('/portal/admin/operations/company', [])
        ->assertSessionHasErrors('name');

    $this->actingAs($superAdmin)
        ->post('/portal/admin/operations/user', [])
        ->assertSessionHasErrors('company_id');

    $this->actingAs($superAdmin)
        ->post('/portal/admin/operations/subscription', [])
        ->assertSessionHasErrors('company_id');
});
