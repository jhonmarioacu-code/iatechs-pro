<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('denies read access to new api modules when user has no permissions', function (): void {
    $company = sec_create_company('New Modules Read Co', 'new-modules-read-co');
    $userWithoutPermissions = sec_create_user(
        $company,
        'new-modules-read-no-perm@example.com',
        'owner'
    );

    $readEndpoints = [
        '/api/v1/suppliers',
        '/api/v1/products',
        '/api/v1/purchase-orders',
        '/api/v1/goods-receipts',
        '/api/v1/inventory',
        '/api/v1/stock-transfers',
        '/api/v1/reports',
        '/api/v1/warranties',
        '/api/v1/accounting/accounts',
        '/api/v1/accounting/journal-entries',
        '/api/v1/audit-logs',
        '/api/v1/knowledge-base',
        '/api/v1/crm/leads',
        '/api/v1/crm/opportunities',
        '/api/v1/crm/pipeline',
        '/api/v1/notifications',
    ];

    foreach ($readEndpoints as $endpoint) {
        $this->actingAs($userWithoutPermissions, 'sanctum')
            ->getJson($endpoint)
            ->assertForbidden();
    }
});

it('denies write access to new api modules when user has no permissions', function (): void {
    $company = sec_create_company('New Modules Write Co', 'new-modules-write-co');
    $userWithoutPermissions = sec_create_user(
        $company,
        'new-modules-write-no-perm@example.com',
        'owner'
    );

    $writeEndpoints = [
        '/api/v1/suppliers',
        '/api/v1/products',
        '/api/v1/purchase-orders',
        '/api/v1/goods-receipts',
        '/api/v1/inventory',
        '/api/v1/stock-transfers',
        '/api/v1/reports',
        '/api/v1/warranties',
        '/api/v1/accounting/accounts',
        '/api/v1/accounting/journal-entries',
        '/api/v1/knowledge-base',
        '/api/v1/notifications',
    ];

    foreach ($writeEndpoints as $endpoint) {
        $this->actingAs($userWithoutPermissions, 'sanctum')
            ->postJson($endpoint, [])
            ->assertForbidden();
    }
});

