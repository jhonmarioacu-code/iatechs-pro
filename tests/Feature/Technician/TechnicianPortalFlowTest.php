<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use App\Domains\Notifications\Models\Notification;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Quotes\Models\Quote;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Repairs\Models\Repair;
use App\Domains\Diagnostics\Models\Diagnostic;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('runs technician portal flow end to end: assigned -> diagnostic -> repair -> close', function (): void {
    $company = sec_create_company('Tech Flow Co', 'tech-flow-co');
    $branch = sec_create_branch($company, 'TF01');
    $customer = sec_create_customer($company, $branch, 'TF01');
    $device = sec_create_device($company, $branch, $customer, 'TF01');

    $technician = sec_create_user(
        $company,
        'tech-flow@example.com',
        'technician',
        [
            'tickets.view',
            'tickets.update',
            'tickets.close',
            'diagnostics.view',
            'diagnostics.create',
            'diagnostics.start',
            'diagnostics.complete',
            'repairs.view',
            'repairs.create',
            'repairs.start',
            'repairs.complete',
        ]
    );

    $ticket = Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'technician_id' => $technician->id,
        'ticket_number' => 'TK-TECH-FLOW-01',
        'status' => 'ASSIGNED',
        'priority' => 'MEDIUM',
        'channel' => 'WEB',
        'reported_problem' => 'No enciende despues de actualizacion.',
        'received_at' => now(),
    ]);

    $this->actingAs($technician)
        ->get(route('portal.technician.dashboard'))
        ->assertOk()
        ->assertSee('TK-TECH-FLOW-01');

    $this->actingAs($technician)
        ->get(route('portal.technician.tickets.show', $ticket))
        ->assertOk();

    $this->actingAs($technician)
        ->post(route('portal.technician.tickets.diagnostics.store', $ticket), [
            'reported_problem' => 'No enciende y no carga bateria despues de actualizacion.',
        ])
        ->assertRedirect();

    $diagnostic = Diagnostic::query()->where('ticket_id', $ticket->id)->firstOrFail();

    $this->actingAs($technician)
        ->post(route('portal.technician.diagnostics.start', $diagnostic))
        ->assertRedirect();

    $this->actingAs($technician)
        ->post(route('portal.technician.diagnostics.complete', $diagnostic), [
            'diagnostic_result' => 'Falla de bateria y controlador de energia.',
            'recommended_solution' => 'Reemplazo de bateria y recalibracion de firmware.',
            'estimated_cost' => 150,
            'estimated_hours' => 2,
        ])
        ->assertRedirect();

    $quote = Quote::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'ticket_id' => $ticket->id,
        'diagnostic_id' => $diagnostic->id,
        'quote_number' => 'QT-TECH-FLOW-01',
        'status' => 'APPROVED',
        'subtotal' => 150,
        'tax' => 0,
        'discount' => 0,
        'total' => 150,
    ]);

    $this->actingAs($technician)
        ->post(route('portal.technician.tickets.repairs.store', $ticket), [
            'quote_id' => $quote->id,
            'repair_notes' => 'Cambio de bateria realizado.',
            'labor_cost' => 50,
            'parts_cost' => 100,
        ])
        ->assertRedirect();

    $repair = Repair::query()->where('ticket_id', $ticket->id)->firstOrFail();

    $this->actingAs($technician)
        ->post(route('portal.technician.repairs.start', $repair))
        ->assertRedirect();

    $this->actingAs($technician)
        ->post(route('portal.technician.repairs.complete', $repair))
        ->assertRedirect();

    $this->actingAs($technician)
        ->post(route('portal.technician.tickets.close', $ticket))
        ->assertRedirect(route('portal.technician.dashboard'));

    expect($diagnostic->fresh()->status)->toBe('COMPLETED');
    expect($repair->fresh()->status)->toBe('COMPLETED');
    expect($ticket->fresh()->status)->toBe('CLOSED');
});

it('forbids technician actions on tickets assigned to another technician', function (): void {
    $company = sec_create_company('Tech Guard Co', 'tech-guard-co');
    $branch = sec_create_branch($company, 'TG01');
    $customer = sec_create_customer($company, $branch, 'TG01');
    $device = sec_create_device($company, $branch, $customer, 'TG01');

    $techA = sec_create_user(
        $company,
        'tech-a@example.com',
        'technician',
        ['tickets.view', 'diagnostics.create']
    );
    $techB = sec_create_user(
        $company,
        'tech-b@example.com',
        'technician',
        ['tickets.view', 'diagnostics.create']
    );

    $ticket = Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'technician_id' => $techA->id,
        'ticket_number' => 'TK-TECH-GUARD-01',
        'status' => 'ASSIGNED',
        'priority' => 'MEDIUM',
        'channel' => 'WEB',
        'reported_problem' => 'Pantalla en negro.',
        'received_at' => now(),
    ]);

    $this->actingAs($techB)
        ->get(route('portal.technician.tickets.show', $ticket))
        ->assertForbidden();

    $this->actingAs($techB)
        ->post(route('portal.technician.tickets.diagnostics.store', $ticket), [
            'reported_problem' => 'Intento de acceso no autorizado a ticket asignado.',
        ])
        ->assertForbidden();
});

it('validates required fields in technician flow actions', function (): void {
    $company = sec_create_company('Tech Validation Co', 'tech-validation-co');
    $branch = sec_create_branch($company, 'TV01');
    $customer = sec_create_customer($company, $branch, 'TV01');
    $device = sec_create_device($company, $branch, $customer, 'TV01');
    $technician = sec_create_user(
        $company,
        'tech-validation@example.com',
        'technician',
        ['tickets.view', 'diagnostics.create']
    );

    $ticket = Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'technician_id' => $technician->id,
        'ticket_number' => 'TK-TECH-VAL-01',
        'status' => 'ASSIGNED',
        'priority' => 'MEDIUM',
        'channel' => 'WEB',
        'reported_problem' => 'No carga.',
        'received_at' => now(),
    ]);

    $this->actingAs($technician)
        ->from(route('portal.technician.tickets.show', $ticket))
        ->post(route('portal.technician.tickets.diagnostics.store', $ticket), [
            'reported_problem' => '',
        ])
        ->assertRedirect(route('portal.technician.tickets.show', $ticket))
        ->assertSessionHasErrors(['reported_problem']);
});

it('enforces diagnostics api permissions for technician role', function (): void {
    $company = sec_create_company('Tech Api Co', 'tech-api-co');
    $technicianNoPermission = sec_create_user(
        $company,
        'tech-api-denied@example.com',
        'technician'
    );
    $technicianWithPermission = sec_create_user(
        $company,
        'tech-api-allow@example.com',
        'technician',
        ['diagnostics.view']
    );

    $this->actingAs($technicianNoPermission, 'sanctum')
        ->getJson('/api/v1/diagnostics')
        ->assertForbidden();

    $this->actingAs($technicianWithPermission, 'sanctum')
        ->getJson('/api/v1/diagnostics')
        ->assertOk()
        ->assertJsonStructure(['data']);
});

it('submits quote for customer approval and creates multichannel notifications', function (): void {
    $company = sec_create_company('Tech Quote Co', 'tech-quote-co');
    $branch = sec_create_branch($company, 'TQ01');
    $customer = sec_create_customer($company, $branch, 'TQ01', 'tq-customer@example.com');
    $customer->update([
        'mobile' => '3001234567',
    ]);
    $device = sec_create_device($company, $branch, $customer, 'TQ01');

    $technician = sec_create_user(
        $company,
        'tech-quote@example.com',
        'technician',
        [
            'tickets.view',
            'diagnostics.create',
            'diagnostics.start',
            'diagnostics.complete',
            'quotes.create',
            'quotes.update',
        ]
    );

    $ticket = Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'technician_id' => $technician->id,
        'ticket_number' => 'TK-TECH-QUOTE-01',
        'status' => 'ASSIGNED',
        'priority' => 'MEDIUM',
        'channel' => 'WEB',
        'reported_problem' => 'No enciende despues de una actualizacion.',
        'received_at' => now(),
    ]);

    $this->actingAs($technician)
        ->post(route('portal.technician.tickets.diagnostics.store', $ticket), [
            'reported_problem' => 'No enciende despues de una actualizacion de sistema y bateria agotada.',
        ])
        ->assertRedirect();

    $diagnostic = Diagnostic::query()->where('ticket_id', $ticket->id)->firstOrFail();

    $this->actingAs($technician)
        ->post(route('portal.technician.diagnostics.start', $diagnostic))
        ->assertRedirect();

    $this->actingAs($technician)
        ->post(route('portal.technician.diagnostics.complete', $diagnostic), [
            'diagnostic_result' => 'Controlador de energia dañado y bateria degradada.',
            'recommended_solution' => 'Cambio de bateria y ajuste de controlador de energia.',
            'estimated_cost' => 220,
            'estimated_hours' => 3,
        ])
        ->assertRedirect();

    $this->actingAs($technician)
        ->post(route('portal.technician.tickets.quotes.submit', $ticket), [
            'subtotal' => 220,
            'tax' => 0,
            'discount' => 0,
            'notes' => 'Incluye repuesto y mano de obra especializada.',
            'channels' => ['EMAIL', 'SMS', 'WHATSAPP'],
        ])
        ->assertRedirect();

    $quote = Quote::query()->where('ticket_id', $ticket->id)->latest()->first();

    expect($quote)->not()->toBeNull();
    expect($quote?->status)->toBe('PENDING_APPROVAL');
    expect($ticket->fresh()->status)->toBe('WAITING_QUOTE');

    $channels = Notification::query()
        ->where('company_id', $company->id)
        ->where('type', 'quote_ready')
        ->pluck('channel')
        ->all();

    expect($channels)->toContain('EMAIL');
    expect($channels)->toContain('SMS');
    expect($channels)->toContain('WHATSAPP');
});

it('allows technician to take available open ticket and assign it to self', function (): void {
    $company = sec_create_company('Take Ticket Co', 'take-ticket-co');
    $branch = sec_create_branch($company, 'TT01');
    $customer = sec_create_customer($company, $branch, 'TT01');
    $device = sec_create_device($company, $branch, $customer, 'TT01');

    $technician = sec_create_user(
        $company,
        'take-tech@example.com',
        'technician',
        ['tickets.view', 'tickets.update']
    );

    $ticket = Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'ticket_number' => 'TK-TAKE-01',
        'status' => 'OPEN',
        'priority' => 'HIGH',
        'channel' => 'WEB',
        'reported_problem' => 'No enciende.',
        'received_at' => now(),
    ]);

    $this->actingAs($technician)
        ->post(route('portal.technician.tickets.take', $ticket))
        ->assertRedirect(route('portal.technician.tickets.show', $ticket));

    $ticket->refresh();

    expect($ticket->technician_id)->toBe($technician->id);
    expect($ticket->status)->toBe('ASSIGNED');
});

it('allows technician to update device repair assets and enable boardview', function (): void {
    $company = sec_create_company('Repair Assets Co', 'repair-assets-co');
    $branch = sec_create_branch($company, 'RA01');
    $customer = sec_create_customer($company, $branch, 'RA01');
    $device = sec_create_device($company, $branch, $customer, 'RA01');

    $technician = sec_create_user(
        $company,
        'assets-tech@example.com',
        'technician',
        ['tickets.view', 'tickets.update']
    );

    $ticket = Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'technician_id' => $technician->id,
        'ticket_number' => 'TK-ASSETS-01',
        'status' => 'IN_REPAIR',
        'priority' => 'MEDIUM',
        'channel' => 'WEB',
        'reported_problem' => 'No detecta energia.',
        'received_at' => now(),
    ]);

    $this->actingAs($technician)
        ->post(route('portal.technician.tickets.repair-assets.update', $ticket), [
            'manual_url' => 'https://example.com/manual.pdf',
            'diagram_url' => 'https://example.com/diagram.pdf',
            'boardview_url' => 'https://example.com/boardview.brd',
            'boardview_enabled' => 1,
        ])
        ->assertRedirect();

    $device->refresh();

    expect($device->manual_url)->toBe('https://example.com/manual.pdf');
    expect($device->diagram_url)->toBe('https://example.com/diagram.pdf');
    expect($device->boardview_url)->toBe('https://example.com/boardview.brd');
    expect($device->boardview_enabled)->toBeTrue();
});
