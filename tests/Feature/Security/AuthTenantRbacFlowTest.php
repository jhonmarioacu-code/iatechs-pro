<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Companies\Models\Company;
use App\Domains\Customers\Models\Customer;
use App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

function createCompany(string $name, string $slug): Company
{
    return Company::create([
        'uuid' => (string) Str::uuid(),
        'name' => $name,
        'slug' => $slug,
        'country' => 'Colombia',
        'status' => Company::STATUS_ACTIVE,
    ]);
}

function createUserWithRole(
    Company $company,
    string $email,
    string $roleName,
    array $permissions = []
): User {
    $user = User::create([
        'company_id' => $company->id,
        'uuid' => (string) Str::uuid(),
        'name' => 'Test User '.$roleName,
        'email' => $email,
        'password' => 'Secret123!',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    $role = Role::firstOrCreate([
        'name' => $roleName,
        'guard_name' => 'web',
    ]);

    foreach ($permissions as $permissionName) {
        $permission = Permission::firstOrCreate([
            'name' => $permissionName,
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo($permission);
    }

    $user->assignRole($role);

    return $user;
}

it('redirects login to the portal based on role', function (): void {
    $company = createCompany('Alpha Tech', 'alpha-tech');
    $user = createUserWithRole(
        $company,
        'owner@example.com',
        'owner'
    );

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'Secret123!',
    ]);

    $response->assertRedirect(route('portal.company.dashboard'));
    $this->assertAuthenticatedAs($user);
});

it('keeps portal dashboards enabled for official roles', function (): void {
    $company = createCompany('Portal Roles Co', 'portal-roles-co');

    $matrix = [
        ['role' => 'owner', 'expected' => 'portal.company.dashboard', 'permissions' => []],
        ['role' => 'administrator', 'expected' => 'portal.company.dashboard', 'permissions' => []],
        ['role' => 'manager', 'expected' => 'portal.company.dashboard', 'permissions' => []],
        ['role' => 'receptionist', 'expected' => 'portal.company.dashboard', 'permissions' => []],
        ['role' => 'warehouse', 'expected' => 'portal.company.dashboard', 'permissions' => []],
        ['role' => 'accountant', 'expected' => 'portal.company.dashboard', 'permissions' => []],
        ['role' => 'technician', 'expected' => 'portal.technician.dashboard', 'permissions' => []],
        [
            'role' => 'customer',
            'expected' => 'portal.customer.dashboard',
            'permissions' => ['customer.portal.view'],
        ],
    ];

    foreach ($matrix as $index => $entry) {
        $email = 'portal-role-'.$entry['role'].'-'.$index.'@example.com';
        $user = createUserWithRole($company, $email, $entry['role'], $entry['permissions']);

        if ($entry['role'] === 'customer') {
            Customer::create([
                'company_id' => $company->id,
                'uuid' => (string) Str::uuid(),
                'customer_code' => 'CUS-PORTAL-'.$index,
                'customer_type' => 'person',
                'first_name' => 'Portal',
                'email' => $email,
            ]);
        }

        $this->post('/login', [
            'email' => $email,
            'password' => 'Secret123!',
        ])->assertRedirect(route($entry['expected']));

        $this->get(route($entry['expected']))->assertOk();
        $this->post('/logout')->assertRedirect(route('login'));
    }
});

it('denies access when user tries to open another portal role', function (): void {
    $company = createCompany('Customer Corp', 'customer-corp');
    $customerUser = createUserWithRole(
        $company,
        'customer@example.com',
        'customer'
    );

    $this->actingAs($customerUser)
        ->get('/portal/admin')
        ->assertForbidden();
});

it('applies tenant isolation on customers api list', function (): void {
    $companyA = createCompany('Company A', 'company-a');
    $companyB = createCompany('Company B', 'company-b');

    $userA = createUserWithRole(
        $companyA,
        'a@example.com',
        'owner',
        ['customers.view']
    );

    createUserWithRole(
        $companyB,
        'b@example.com',
        'owner',
        ['customers.view']
    );

    $customerA = Customer::create([
        'company_id' => $companyA->id,
        'uuid' => (string) Str::uuid(),
        'customer_code' => 'CUS-A-001',
        'customer_type' => 'person',
        'first_name' => 'Alice',
    ]);

    Customer::create([
        'company_id' => $companyB->id,
        'uuid' => (string) Str::uuid(),
        'customer_code' => 'CUS-B-001',
        'customer_type' => 'person',
        'first_name' => 'Bob',
    ]);

    $response = $this->actingAs($userA, 'sanctum')
        ->getJson('/api/v1/customers');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.id', $customerA->id);
});

it('allows authenticated web session to consume internal api endpoints', function (): void {
    $company = createCompany('Session Co', 'session-co');
    $user = createUserWithRole(
        $company,
        'session@example.com',
        'owner',
        ['customers.view']
    );

    Customer::create([
        'company_id' => $company->id,
        'uuid' => (string) Str::uuid(),
        'customer_code' => 'CUS-S-001',
        'customer_type' => 'person',
        'first_name' => 'Session',
    ]);

    $response = $this->actingAs($user, 'web')
        ->getJson('/api/v1/customers');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
});
