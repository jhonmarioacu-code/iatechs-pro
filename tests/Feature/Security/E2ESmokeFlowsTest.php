<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('smoke flow: owner login, portal redirect and minimal company crud pages', function (): void {
    $company = sec_create_company('Smoke Owner Co', 'smoke-owner-co');
    $owner = sec_create_user(
        $company,
        'owner-smoke@example.com',
        'owner',
        ['customers.view']
    );

    $branch = sec_create_branch($company, 'SMK1');
    sec_create_customer($company, $branch, 'SMK1');

    $this->post('/login', [
        'email' => $owner->email,
        'password' => 'Secret123!',
    ])->assertRedirect(route('portal.company.dashboard'));

    $this->get('/portal/company')
        ->assertOk();

    $this->get('/portal/company/customers')
        ->assertOk();

    $this->getJson('/api/v1/customers')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('smoke flow: role based redirect for super admin, technician and customer', function (): void {
    $company = sec_create_company('Smoke Roles Co', 'smoke-roles-co');

    $superAdmin = sec_create_user(
        $company,
        'super-smoke@example.com',
        'super_admin'
    );
    $technician = sec_create_user(
        $company,
        'tech-smoke@example.com',
        'technician'
    );
    $customer = sec_create_user(
        $company,
        'customer-smoke@example.com',
        'customer'
    );

    $this->post('/login', [
        'email' => $superAdmin->email,
        'password' => 'Secret123!',
    ])->assertRedirect(route('portal.admin.dashboard'));
    $this->post('/logout')->assertRedirect(route('login'));

    $this->post('/login', [
        'email' => $technician->email,
        'password' => 'Secret123!',
    ])->assertRedirect(route('portal.technician.dashboard'));
    $this->post('/logout')->assertRedirect(route('login'));

    $this->post('/login', [
        'email' => $customer->email,
        'password' => 'Secret123!',
    ])->assertRedirect(route('portal.customer.dashboard'));
});
