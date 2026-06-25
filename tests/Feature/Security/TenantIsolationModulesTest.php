<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('isolates customers, devices, tickets, invoices and payments by tenant company', function (): void {
    $permissions = [
        'customers.view',
        'devices.view',
        'tickets.view',
        'invoices.view',
        'payments.view',
    ];

    $companyA = sec_create_company('Tenant A', 'tenant-a');
    $companyB = sec_create_company('Tenant B', 'tenant-b');

    $userA = sec_create_user(
        $companyA,
        'tenant-a-owner@example.com',
        'owner',
        $permissions
    );
    sec_create_user(
        $companyB,
        'tenant-b-owner@example.com',
        'owner',
        $permissions
    );

    $branchA = sec_create_branch($companyA, 'A001');
    $customerA = sec_create_customer($companyA, $branchA, 'A001');
    $deviceA = sec_create_device($companyA, $branchA, $customerA, 'A001');
    $ticketA = sec_create_ticket($companyA, $branchA, $customerA, $deviceA, 'A001');
    $invoiceA = sec_create_invoice($companyA, $branchA, $customerA, $ticketA, 'A001');
    $paymentA = sec_create_payment($companyA, $branchA, $customerA, $invoiceA, $userA, 'A001');

    $branchB = sec_create_branch($companyB, 'B001');
    $customerB = sec_create_customer($companyB, $branchB, 'B001');
    $deviceB = sec_create_device($companyB, $branchB, $customerB, 'B001');
    $ticketB = sec_create_ticket($companyB, $branchB, $customerB, $deviceB, 'B001');
    $invoiceB = sec_create_invoice($companyB, $branchB, $customerB, $ticketB, 'B001');
    sec_create_payment($companyB, $branchB, $customerB, $invoiceB, $userA, 'B001');

    $this->actingAs($userA, 'sanctum')
        ->getJson('/api/v1/customers')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $customerA->id);

    $this->actingAs($userA, 'sanctum')
        ->getJson('/api/v1/devices')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $deviceA->id);

    $this->actingAs($userA, 'sanctum')
        ->getJson('/api/v1/tickets')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $ticketA->id);

    $this->actingAs($userA, 'sanctum')
        ->getJson('/api/v1/invoices')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $invoiceA->id);

    $this->actingAs($userA, 'sanctum')
        ->getJson('/api/v1/payments')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $paymentA->id);
});
