<?php

declare(strict_types=1);

use App\Domains\Tickets\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('shows company ticket management states, personnel, roles and branches', function (): void {
    $company = sec_create_company('Company Dashboard Co', 'company-dashboard-co');
    $owner = sec_create_user(
        $company,
        'company-owner@example.com',
        'owner',
        ['customers.view']
    );

    $branchA = sec_create_branch($company, 'CDA1');
    $branchB = sec_create_branch($company, 'CDB2');

    $customer = sec_create_customer($company, $branchA, 'CD01');
    $device = sec_create_device($company, $branchA, $customer, 'CD01');

    $technician = sec_create_user(
        $company,
        'company-tech@example.com',
        'technician',
        ['tickets.view']
    );
    $technician->update(['branch_id' => $branchA->id]);

    $administrator = sec_create_user(
        $company,
        'company-admin@example.com',
        'administrator',
        ['users.view']
    );
    $administrator->update(['branch_id' => $branchB->id]);

    Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branchA->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'technician_id' => $technician->id,
        'ticket_number' => 'TK-COMP-APP-01',
        'status' => 'APPROVED',
        'priority' => 'MEDIUM',
        'channel' => 'WEB',
        'reported_problem' => 'Problema aprobado.',
        'received_at' => now(),
    ]);

    Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branchA->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'ticket_number' => 'TK-COMP-PEN-01',
        'status' => 'OPEN',
        'priority' => 'HIGH',
        'channel' => 'WEB',
        'reported_problem' => 'Problema pendiente.',
        'received_at' => now(),
    ]);

    Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branchB->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'ticket_number' => 'TK-COMP-FIN-01',
        'status' => 'CLOSED',
        'priority' => 'LOW',
        'channel' => 'WEB',
        'reported_problem' => 'Problema finalizado.',
        'received_at' => now(),
    ]);

    Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branchB->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'ticket_number' => 'TK-COMP-NC-01',
        'status' => 'CANCELLED',
        'priority' => 'LOW',
        'channel' => 'WEB',
        'reported_problem' => 'Problema no concretado.',
        'received_at' => now(),
    ]);

    $this->actingAs($owner)
        ->get(route('portal.company.dashboard'))
        ->assertOk()
        ->assertSee('Tickets aprobados')
        ->assertSee('Tickets pendientes')
        ->assertSee('Tickets finalizados')
        ->assertSee('Tickets no concretados')
        ->assertSee('Gestion de Tickets por Estado')
        ->assertSee('Aprobada')
        ->assertSee('Pendiente')
        ->assertSee('Finalizada')
        ->assertSee('No Concretada')
        ->assertSee('TK-COMP-APP-01')
        ->assertSee('TK-COMP-PEN-01')
        ->assertSee('TK-COMP-FIN-01')
        ->assertSee('TK-COMP-NC-01')
        ->assertSee('Personal, Funciones, Roles y Sucursal')
        ->assertSee('company-tech@example.com')
        ->assertSee('company-admin@example.com')
        ->assertSee($branchA->name)
        ->assertSee($branchB->name);
});

it('allows company to enable technician courses and exams', function (): void {
    $company = sec_create_company('Company Training Co', 'company-training-co');
    $owner = sec_create_user(
        $company,
        'training-owner@example.com',
        'owner',
        ['customers.view']
    );
    $branch = sec_create_branch($company, 'CT01');

    $technician = sec_create_user(
        $company,
        'training-tech@example.com',
        'technician',
        ['tickets.view']
    );
    $technician->update([
        'branch_id' => $branch->id,
        'technician_course_enabled' => false,
        'technician_exam_enabled' => false,
    ]);

    $this->actingAs($owner)
        ->post(route('portal.company.technicians.training.update', $technician), [
            'technician_course_enabled' => 1,
            'technician_exam_enabled' => 1,
        ])
        ->assertRedirect();

    $technician->refresh();

    expect($technician->technician_course_enabled)->toBeTrue();
    expect($technician->technician_exam_enabled)->toBeTrue();
});
