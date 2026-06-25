<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Payments\Models\Payment;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('handles tickets create update delete and validation/forbidden errors', function (): void {
    $company = sec_create_company('Tickets Co', 'tickets-co');
    $branch = sec_create_branch($company, 'TK01');
    $customer = sec_create_customer($company, $branch, 'TK01');
    $device = sec_create_device($company, $branch, $customer, 'TK01');

    $manager = sec_create_user(
        $company,
        'ticket-manager@example.com',
        'owner',
        ['tickets.create', 'tickets.update', 'tickets.delete']
    );

    $viewer = sec_create_user(
        $company,
        'ticket-viewer@example.com',
        'owner',
        ['tickets.view']
    );

    $this->actingAs($viewer, 'sanctum')
        ->postJson('/api/v1/tickets', [
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'device_id' => $device->id,
            'reported_problem' => 'Pantalla rota por caida.',
        ])
        ->assertStatus(403);

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/tickets', [])
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);

    $create = $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/tickets', [
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'device_id' => $device->id,
            'reported_problem' => 'Pantalla rota por caida en oficina.',
            'priority' => 'HIGH',
            'channel' => 'WEB',
        ])
        ->assertOk()
        ->assertJsonPath('data.priority', 'HIGH');

    $ticketId = (int) $create->json('data.id');

    $this->actingAs($manager, 'sanctum')
        ->putJson("/api/v1/tickets/{$ticketId}", [
            'status' => 'IN_DIAGNOSIS',
            'customer_notes' => 'Equipo urgente',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'IN_DIAGNOSIS');

    $this->actingAs($manager, 'sanctum')
        ->deleteJson("/api/v1/tickets/{$ticketId}")
        ->assertOk()
        ->assertJsonPath('success', true);

    $ticket = Ticket::query()->withTrashed()->findOrFail($ticketId);
    expect($ticket->trashed())->toBeTrue();
});

it('handles invoices create update delete and validation/forbidden errors', function (): void {
    $company = sec_create_company('Invoices Co', 'invoices-co');
    $branch = sec_create_branch($company, 'IN01');
    $customer = sec_create_customer($company, $branch, 'IN01');
    $device = sec_create_device($company, $branch, $customer, 'IN01');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'IN01');

    $manager = sec_create_user(
        $company,
        'invoice-manager@example.com',
        'owner',
        ['invoices.create', 'invoices.update', 'invoices.delete']
    );

    $viewer = sec_create_user(
        $company,
        'invoice-viewer@example.com',
        'owner',
        ['invoices.view']
    );

    $this->actingAs($viewer, 'sanctum')
        ->postJson('/api/v1/invoices', [
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'ticket_id' => $ticket->id,
            'subtotal' => 100,
        ])
        ->assertStatus(403);

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/invoices', [])
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);

    $create = $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/invoices', [
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'ticket_id' => $ticket->id,
            'subtotal' => 100,
            'tax' => 19,
            'discount' => 0,
            'currency' => 'COP',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'draft');

    $invoiceId = (int) $create->json('data.id');

    $this->actingAs($manager, 'sanctum')
        ->putJson("/api/v1/invoices/{$invoiceId}", [
            'status' => 'issued',
            'notes' => 'Factura de prueba',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'issued');

    $this->actingAs($manager, 'sanctum')
        ->deleteJson("/api/v1/invoices/{$invoiceId}")
        ->assertOk()
        ->assertJsonPath('success', true);

    $invoice = Invoice::query()->withTrashed()->findOrFail($invoiceId);
    expect($invoice->trashed())->toBeTrue();
});

it('handles payments create update delete and validation/forbidden errors', function (): void {
    $company = sec_create_company('Payments Co', 'payments-co');
    $branch = sec_create_branch($company, 'PY01');
    $customer = sec_create_customer($company, $branch, 'PY01');
    $device = sec_create_device($company, $branch, $customer, 'PY01');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'PY01');
    $invoice = sec_create_invoice($company, $branch, $customer, $ticket, 'PY01');

    $manager = sec_create_user(
        $company,
        'payment-manager@example.com',
        'owner',
        ['payments.create', 'payments.update', 'payments.delete']
    );

    $viewer = sec_create_user(
        $company,
        'payment-viewer@example.com',
        'owner',
        ['payments.view']
    );

    $this->actingAs($viewer, 'sanctum')
        ->postJson('/api/v1/payments', [
            'branch_id' => $branch->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'payment_method' => 'CASH',
            'amount' => 50,
        ])
        ->assertStatus(403);

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/payments', [])
        ->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);

    $create = $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/payments', [
            'branch_id' => $branch->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'payment_method' => 'CASH',
            'amount' => 50,
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'PENDING');

    $paymentId = (int) $create->json('data.id');

    $this->actingAs($manager, 'sanctum')
        ->putJson("/api/v1/payments/{$paymentId}", [
            'amount' => 60,
            'notes' => 'Ajuste manual',
        ])
        ->assertOk()
        ->assertJsonPath('data.amount', '60.00');

    $this->actingAs($manager, 'sanctum')
        ->deleteJson("/api/v1/payments/{$paymentId}")
        ->assertOk()
        ->assertJsonPath('success', true);

    $payment = Payment::query()->withTrashed()->findOrFail($paymentId);
    expect($payment->trashed())->toBeTrue();
});
