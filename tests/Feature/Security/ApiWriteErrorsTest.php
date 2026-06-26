<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Payments\Models\Payment;
use App\Domains\Diagnostics\Models\Diagnostic;
use App\Domains\Quotes\Models\Quote;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('handles tickets create update delete and validation/forbidden errors', function (): void {
    $company = sec_create_company('Tickets Co', 'tickets-co');
    $branch = sec_create_branch($company, 'TK01');
    $customer = sec_create_customer($company, $branch, 'TK01');
    $device = sec_create_device($company, $branch, $customer, 'TK01');
    $foreignCompany = sec_create_company('Tickets Foreign Co', 'tickets-foreign-co');
    $foreignBranch = sec_create_branch($foreignCompany, 'TK99');
    $foreignCustomer = sec_create_customer($foreignCompany, $foreignBranch, 'TK99');
    $foreignDevice = sec_create_device($foreignCompany, $foreignBranch, $foreignCustomer, 'TK99');

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

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/tickets', [
            'company_id' => $company->id,
            'branch_id' => $foreignBranch->id,
            'customer_id' => $foreignCustomer->id,
            'device_id' => $foreignDevice->id,
            'reported_problem' => 'Intento de referencia cruzada entre empresas.',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'branch_id',
            'customer_id',
            'device_id',
        ]);

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

it('handles customers create with tenant-scoped validation errors', function (): void {
    $company = sec_create_company('Customers Co', 'customers-co');
    $branch = sec_create_branch($company, 'CU01');

    $foreignCompany = sec_create_company('Customers Foreign Co', 'customers-foreign-co');
    $foreignBranch = sec_create_branch($foreignCompany, 'CU99');

    $manager = sec_create_user(
        $company,
        'customer-manager@example.com',
        'owner',
        ['customers.create']
    );

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/customers', [
            'branch_id' => $foreignBranch->id,
            'customer_type' => 'person',
            'first_name' => 'Cross',
            'email' => 'cross-customer@example.com',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['branch_id']);

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/customers', [
            'branch_id' => $branch->id,
            'customer_type' => 'person',
            'first_name' => 'Valid',
            'email' => 'valid-customer@example.com',
        ])
        ->assertStatus(201)
        ->assertJsonPath('data.company_id', $company->id);
});

it('handles devices create with tenant-scoped validation errors', function (): void {
    $company = sec_create_company('Devices Co', 'devices-co');
    $branch = sec_create_branch($company, 'DV01');
    $customer = sec_create_customer($company, $branch, 'DV01');

    $foreignCompany = sec_create_company('Devices Foreign Co', 'devices-foreign-co');
    $foreignBranch = sec_create_branch($foreignCompany, 'DV99');
    $foreignCustomer = sec_create_customer($foreignCompany, $foreignBranch, 'DV99');

    $manager = sec_create_user(
        $company,
        'device-manager@example.com',
        'owner',
        ['devices.create']
    );

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/devices', [
            'branch_id' => $foreignBranch->id,
            'customer_id' => $foreignCustomer->id,
            'device_type' => 'Laptop',
            'brand' => 'Brand',
            'model' => 'Model X',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['branch_id', 'customer_id']);

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/devices', [
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'device_type' => 'Laptop',
            'brand' => 'Brand',
            'model' => 'Model X',
        ])
        ->assertStatus(201)
        ->assertJsonPath('data.company_id', $company->id);
});

it('handles diagnostics create with tenant-scoped validation errors', function (): void {
    $company = sec_create_company('Diagnostics Co', 'diagnostics-co');
    $branch = sec_create_branch($company, 'DG01');
    $customer = sec_create_customer($company, $branch, 'DG01');
    $device = sec_create_device($company, $branch, $customer, 'DG01');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'DG01');
    $technician = sec_create_user($company, 'diag-tech@example.com', 'technician');

    $foreignCompany = sec_create_company('Diagnostics Foreign Co', 'diagnostics-foreign-co');
    $foreignBranch = sec_create_branch($foreignCompany, 'DG99');
    $foreignCustomer = sec_create_customer($foreignCompany, $foreignBranch, 'DG99');
    $foreignDevice = sec_create_device($foreignCompany, $foreignBranch, $foreignCustomer, 'DG99');
    $foreignTicket = sec_create_ticket($foreignCompany, $foreignBranch, $foreignCustomer, $foreignDevice, 'DG99');
    $foreignTech = sec_create_user($foreignCompany, 'diag-foreign-tech@example.com', 'technician');

    $manager = sec_create_user(
        $company,
        'diag-manager@example.com',
        'owner',
        ['diagnostics.create']
    );

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/diagnostics', [
            'branch_id' => $foreignBranch->id,
            'ticket_id' => $foreignTicket->id,
            'technician_id' => $foreignTech->id,
            'reported_problem' => 'Cross tenant diagnostic creation attempt.',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['branch_id', 'ticket_id', 'technician_id']);

    $create = $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/diagnostics', [
            'branch_id' => $branch->id,
            'ticket_id' => $ticket->id,
            'technician_id' => $technician->id,
            'reported_problem' => 'Diagnostic problem details for device validation.',
        ])
        ->assertStatus(201);

    $diagnosticId = (int) $create->json('data.id');
    $diagnostic = Diagnostic::query()->findOrFail($diagnosticId);
    expect($diagnostic->company_id)->toBe($company->id);
});

it('handles admin quotes create with tenant-scoped validation errors', function (): void {
    $company = sec_create_company('Quotes Co', 'quotes-co');
    $branch = sec_create_branch($company, 'QT01');
    $customer = sec_create_customer($company, $branch, 'QT01');
    $device = sec_create_device($company, $branch, $customer, 'QT01');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'QT01');

    $diagnostic = Diagnostic::query()->create([
        'uuid' => (string) \Illuminate\Support\Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'ticket_id' => $ticket->id,
        'diagnostic_code' => 'DG-QT01',
        'status' => 'COMPLETED',
        'reported_problem' => 'Board short circuit and intermittent boot failures.',
    ]);

    $foreignCompany = sec_create_company('Quotes Foreign Co', 'quotes-foreign-co');
    $foreignBranch = sec_create_branch($foreignCompany, 'QT99');
    $foreignCustomer = sec_create_customer($foreignCompany, $foreignBranch, 'QT99');
    $foreignDevice = sec_create_device($foreignCompany, $foreignBranch, $foreignCustomer, 'QT99');
    $foreignTicket = sec_create_ticket($foreignCompany, $foreignBranch, $foreignCustomer, $foreignDevice, 'QT99');

    $superAdmin = sec_create_user(
        $company,
        'quotes-superadmin@example.com',
        'super_admin',
        ['quotes.create']
    );

    $this->actingAs($superAdmin, 'web')
        ->postJson('/admin/quotes', [
            'company_id' => $company->id,
            'branch_id' => $foreignBranch->id,
            'ticket_id' => $foreignTicket->id,
            'diagnostic_id' => $diagnostic->id,
            'subtotal' => 100,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['branch_id', 'ticket_id']);

    $create = $this->actingAs($superAdmin, 'web')
        ->postJson('/admin/quotes', [
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'ticket_id' => $ticket->id,
            'diagnostic_id' => $diagnostic->id,
            'subtotal' => 100,
            'tax' => 19,
            'discount' => 0,
        ])
        ->assertStatus(201);

    $quoteId = (int) $create->json('data.id');
    $quote = Quote::query()->findOrFail($quoteId);
    expect($quote->company_id)->toBe($company->id);
});

it('handles invoices create update delete and validation/forbidden errors', function (): void {
    $company = sec_create_company('Invoices Co', 'invoices-co');
    $branch = sec_create_branch($company, 'IN01');
    $customer = sec_create_customer($company, $branch, 'IN01');
    $device = sec_create_device($company, $branch, $customer, 'IN01');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'IN01');
    $foreignCompany = sec_create_company('Invoices Foreign Co', 'invoices-foreign-co');
    $foreignBranch = sec_create_branch($foreignCompany, 'IN99');
    $foreignCustomer = sec_create_customer($foreignCompany, $foreignBranch, 'IN99');
    $foreignDevice = sec_create_device($foreignCompany, $foreignBranch, $foreignCustomer, 'IN99');
    $foreignTicket = sec_create_ticket($foreignCompany, $foreignBranch, $foreignCustomer, $foreignDevice, 'IN99');

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

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/invoices', [
            'branch_id' => $foreignBranch->id,
            'customer_id' => $foreignCustomer->id,
            'ticket_id' => $foreignTicket->id,
            'subtotal' => 100,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'branch_id',
            'customer_id',
            'ticket_id',
        ]);

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
    $foreignCompany = sec_create_company('Payments Foreign Co', 'payments-foreign-co');
    $foreignBranch = sec_create_branch($foreignCompany, 'PY99');
    $foreignCustomer = sec_create_customer($foreignCompany, $foreignBranch, 'PY99');
    $foreignDevice = sec_create_device($foreignCompany, $foreignBranch, $foreignCustomer, 'PY99');
    $foreignTicket = sec_create_ticket($foreignCompany, $foreignBranch, $foreignCustomer, $foreignDevice, 'PY99');
    $foreignInvoice = sec_create_invoice($foreignCompany, $foreignBranch, $foreignCustomer, $foreignTicket, 'PY99');

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

    $this->actingAs($manager, 'sanctum')
        ->postJson('/api/v1/payments', [
            'branch_id' => $foreignBranch->id,
            'invoice_id' => $foreignInvoice->id,
            'customer_id' => $foreignCustomer->id,
            'payment_method' => 'CASH',
            'amount' => 50,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'branch_id',
            'invoice_id',
            'customer_id',
        ]);

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
