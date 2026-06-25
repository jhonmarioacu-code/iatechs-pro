<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('returns api contract for health endpoints', function (): void {
    $health = $this->getJson('/health');
    expect(in_array($health->status(), [200, 503], true))->toBeTrue();
    $health->assertJsonStructure([
        'success',
        'service',
        'status',
        'checks' => [
            'database',
            'redis',
            'queue',
            'storage',
        ],
        'metrics' => [
            'requests_total',
            'last_duration_ms',
            'last_seen_at',
        ],
    ]);

    $apiHealth = $this->getJson('/api/health');
    expect(in_array($apiHealth->status(), [200, 503], true))->toBeTrue();
    $apiHealth->assertJsonStructure([
        'success',
        'service',
        'version',
        'status',
        'checks',
        'metrics',
    ]);
});

it('enforces auth and permission contracts on api endpoints', function (): void {
    $this->getJson('/api/v1/customers')
        ->assertStatus(401);

    $company = sec_create_company('RBAC Co', 'rbac-co');
    $ownerWithoutPermission = sec_create_user(
        $company,
        'owner-no-perm@example.com',
        'owner'
    );

    $this->actingAs($ownerWithoutPermission, 'sanctum')
        ->getJson('/api/v1/customers')
        ->assertStatus(403);

    $ownerWithPermission = sec_create_user(
        $company,
        'owner-with-perm@example.com',
        'owner',
        ['customers.create']
    );

    $this->actingAs($ownerWithPermission, 'sanctum')
        ->postJson('/api/v1/customers', [])
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors',
        ]);
});

it('enforces web rbac on admin and observability routes', function (): void {
    $company = sec_create_company('Admin Co', 'admin-co');
    $owner = sec_create_user(
        $company,
        'owner-admin@example.com',
        'owner'
    );
    $superAdmin = sec_create_user(
        $company,
        'super-admin@example.com',
        'super_admin'
    );

    $this->get('/admin/observability')
        ->assertRedirect(route('login'));

    $this->actingAs($owner)
        ->get('/admin/observability')
        ->assertForbidden();

    $this->actingAs($owner)
        ->get('/portal/admin')
        ->assertForbidden();

    $this->actingAs($superAdmin)
        ->get('/portal/admin')
        ->assertOk();

    $this->actingAs($superAdmin)
        ->get('/admin/observability')
        ->assertOk();
});

it('blocks operations endpoints for non super admin users', function (): void {
    $company = sec_create_company('Ops Guard Co', 'ops-guard-co');
    $owner = sec_create_user(
        $company,
        'owner-ops-guard@example.com',
        'owner'
    );

    $this->actingAs($owner)
        ->post('/portal/admin/operations/company', [
            'name' => 'Should Not Create',
        ])
        ->assertForbidden();
});
