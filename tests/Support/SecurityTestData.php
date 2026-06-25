<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Domains\Plans\Models\Plan;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Devices\Models\Device;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Payments\Models\Payment;
use App\Domains\Branches\Models\Branch;
use App\Domains\Customers\Models\Customer;
use App\Domains\Companies\Models\Company;

if (!function_exists('sec_create_company')) {
    function sec_create_company(string $name, string $slug): Company
    {
        return Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'slug' => $slug,
            'status' => Company::STATUS_ACTIVE,
            'country' => 'CO',
        ]);
    }
}

if (!function_exists('sec_create_role')) {
    function sec_create_role(string $roleName, array $permissions = []): Role
    {
        $role = Role::query()->firstOrCreate([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        // Keep role semantics stable; per-test granular permissions are attached to user.

        return $role;
    }
}

if (!function_exists('sec_create_user')) {
    function sec_create_user(
        Company $company,
        string $email,
        string $roleName,
        array $permissions = [],
        bool $isActive = true,
        string $password = 'Secret123!'
    ): User {
        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'name' => 'User '.Str::upper($roleName),
            'email' => $email,
            'password' => $password,
            'is_active' => $isActive,
            'email_verified_at' => now(),
        ]);

        $role = sec_create_role($roleName, $permissions);
        $user->assignRole($role);

        foreach ($permissions as $permissionName) {
            $permission = Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            $user->givePermissionTo($permission);
        }

        return $user;
    }
}

if (!function_exists('sec_create_branch')) {
    function sec_create_branch(Company $company, string $code): Branch
    {
        return Branch::query()->create([
            'company_id' => $company->id,
            'uuid' => (string) Str::uuid(),
            'name' => 'Branch '.$code,
            'slug' => 'branch-'.$code,
            'code' => $code,
            'country' => 'CO',
            'timezone' => 'America/Bogota',
            'is_active' => true,
        ]);
    }
}

if (!function_exists('sec_create_customer')) {
    function sec_create_customer(Company $company, Branch $branch, string $suffix, ?string $email = null): Customer
    {
        return Customer::query()->create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'uuid' => (string) Str::uuid(),
            'customer_code' => 'CUS-'.$suffix,
            'customer_type' => 'person',
            'first_name' => 'Customer '.$suffix,
            'email' => $email,
            'country' => 'CO',
            'is_active' => true,
        ]);
    }
}

if (!function_exists('sec_create_device')) {
    function sec_create_device(Company $company, Branch $branch, Customer $customer, string $suffix): Device
    {
        return Device::query()->create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'uuid' => (string) Str::uuid(),
            'device_type' => 'Laptop',
            'brand' => 'Brand '.$suffix,
            'model' => 'Model '.$suffix,
            'serial_number' => 'SER-'.$suffix,
            'is_active' => true,
        ]);
    }
}

if (!function_exists('sec_create_ticket')) {
    function sec_create_ticket(
        Company $company,
        Branch $branch,
        Customer $customer,
        Device $device,
        string $suffix
    ): Ticket {
        return Ticket::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'device_id' => $device->id,
            'ticket_number' => 'TK-'.$suffix,
            'status' => 'OPEN',
            'priority' => 'MEDIUM',
            'channel' => 'WEB',
            'reported_problem' => 'Problem '.$suffix,
            'received_at' => now(),
            'is_warranty' => false,
        ]);
    }
}

if (!function_exists('sec_create_invoice')) {
    function sec_create_invoice(
        Company $company,
        Branch $branch,
        Customer $customer,
        ?Ticket $ticket,
        string $suffix
    ): Invoice {
        return Invoice::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'ticket_id' => $ticket?->id,
            'invoice_series' => 'INV',
            'invoice_number' => 'INV-'.$suffix,
            'subtotal' => 100,
            'tax' => 19,
            'discount' => 0,
            'total' => 119,
            'currency' => 'COP',
            'exchange_rate' => 1,
            'status' => 'issued',
        ]);
    }
}

if (!function_exists('sec_create_payment')) {
    function sec_create_payment(
        Company $company,
        Branch $branch,
        Customer $customer,
        Invoice $invoice,
        User $processedBy,
        string $suffix
    ): Payment {
        return Payment::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'processed_by' => $processedBy->id,
            'payment_number' => 'PAY-'.$suffix,
            'payment_method' => 'CASH',
            'currency' => 'COP',
            'amount' => 119,
            'is_partial' => false,
            'status' => 'PENDING',
        ]);
    }
}

if (!function_exists('sec_create_plan')) {
    function sec_create_plan(string $slug, int $monthly = 49, int $yearly = 490): Plan
    {
        return Plan::query()->create([
            'uuid' => (string) Str::uuid(),
            'name' => Str::title(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'monthly_price' => $monthly,
            'yearly_price' => $yearly,
            'trial_days' => 14,
            'status' => 'active',
        ]);
    }
}
