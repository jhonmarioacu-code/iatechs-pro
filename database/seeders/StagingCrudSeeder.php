<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Domains\Users\Models\User;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Devices\Models\Device;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Branches\Models\Branch;
use App\Domains\Customers\Models\Customer;
use App\Domains\Companies\Models\Company;

class StagingCrudSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            SuperAdminPermissionSeeder::class,
        ]);

        $company = Company::updateOrCreate(
            ['slug' => 'iatechs-staging'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'IAtechs Staging',
                'legal_name' => 'IAtechs Staging S.A.S.',
                'tax_id' => '901000000-1',
                'email' => 'staging@iatechs.test',
                'phone' => '+57 300 100 1000',
                'website' => null,
                'address' => 'Calle 100 # 10-10',
                'city' => 'Bogota',
                'country' => 'Colombia',
                'logo' => null,
                'status' => Company::STATUS_ACTIVE,
                'trial_ends_at' => now()->addMonths(1),
            ]
        );

        $branch = Branch::updateOrCreate(
            [
                'company_id' => $company->id,
                'code' => 'MAIN',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Sucursal Principal',
                'slug' => 'sucursal-principal',
                'phone' => '+57 300 100 2000',
                'email' => 'branch@iatechs.test',
                'manager_name' => 'Manager Staging',
                'address' => 'Calle 100 # 10-10',
                'city' => 'Bogota',
                'state' => 'Cundinamarca',
                'country' => 'Colombia',
                'timezone' => 'America/Bogota',
                'is_main' => true,
                'is_active' => true,
                'metadata' => null,
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin.staging@iatechs.test'],
            [
                'company_id' => $company->id,
                'uuid' => (string) Str::uuid(),
                'name' => 'Admin Staging',
                'password' => 'password',
                'phone' => '+57 300 100 3000',
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['super_admin']);

        $technician = User::updateOrCreate(
            ['email' => 'tech.staging@iatechs.test'],
            [
                'company_id' => $company->id,
                'uuid' => (string) Str::uuid(),
                'name' => 'Technician Staging',
                'password' => 'password',
                'phone' => '+57 300 100 4000',
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $technician->syncRoles(['technician']);

        $customer = Customer::updateOrCreate(
            [
                'company_id' => $company->id,
                'customer_code' => 'CUST-STG-0001',
            ],
            [
                'branch_id' => $branch->id,
                'uuid' => (string) Str::uuid(),
                'customer_type' => 'person',
                'first_name' => 'Cliente',
                'last_name' => 'Staging',
                'company_name' => null,
                'document_type' => 'CC',
                'document_number' => '100000001',
                'email' => 'cliente.staging@iatechs.test',
                'phone' => '+57 300 100 5000',
                'mobile' => '+57 300 100 5000',
                'address' => 'Calle 100 # 10-10',
                'city' => 'Bogota',
                'state' => 'Cundinamarca',
                'country' => 'Colombia',
                'credit_limit' => 1500000,
                'balance' => 0,
                'customer_since' => now()->toDateString(),
                'accepts_marketing' => true,
                'notes' => 'Registro de pruebas staging',
                'is_active' => true,
            ]
        );

        $device = Device::updateOrCreate(
            [
                'company_id' => $company->id,
                'serial_number' => 'SER-STG-0001',
            ],
            [
                'branch_id' => $branch->id,
                'customer_id' => $customer->id,
                'uuid' => (string) Str::uuid(),
                'device_type' => 'laptop',
                'brand' => 'Lenovo',
                'model' => 'ThinkPad X1',
                'imei' => null,
                'color' => 'Black',
                'accessories' => 'Cargador',
                'observations' => 'Equipo de pruebas',
                'is_active' => true,
            ]
        );

        $ticket = Ticket::updateOrCreate(
            [
                'company_id' => $company->id,
                'ticket_number' => 'TK-STG-0001',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'branch_id' => $branch->id,
                'customer_id' => $customer->id,
                'device_id' => $device->id,
                'technician_id' => $technician->id,
                'status' => 'ASSIGNED',
                'priority' => 'MEDIUM',
                'channel' => 'COUNTER',
                'reported_problem' => 'Equipo no enciende luego de actualización.',
                'customer_notes' => 'Revisar posible falla de batería.',
                'received_at' => now()->subHours(2),
                'assigned_at' => now()->subHour(),
                'closed_at' => null,
                'is_warranty' => false,
            ]
        );

        Invoice::updateOrCreate(
            [
                'company_id' => $company->id,
                'invoice_number' => 'INV-STG-0001',
            ],
            [
                'uuid' => (string) Str::uuid(),
                'invoice_series' => 'INV',
                'branch_id' => $branch->id,
                'customer_id' => $customer->id,
                'billing_id' => null,
                'ticket_id' => $ticket->id,
                'repair_id' => null,
                'status' => 'draft',
                'subtotal' => 180000,
                'tax' => 34200,
                'discount' => 0,
                'total' => 214200,
                'currency' => 'COP',
                'exchange_rate' => 1,
                'issued_at' => null,
                'due_date' => now()->addDays(10)->toDateString(),
                'paid_at' => null,
                'notes' => 'Factura de prueba staging',
            ]
        );
    }
}
