<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Quotes\Models\Quote;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Payments\Models\Payment;
use App\Domains\Products\Models\Product;
use App\Domains\ServiceContracts\Models\ServiceContract;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('allows customer to login and access customer dashboard with own data and marketplace', function (): void {
    $company = sec_create_company('Customer Portal Co', 'customer-portal-co');
    $branch = sec_create_branch($company, 'CP01');

    $customerUser = sec_create_user(
        $company,
        'customer-portal@example.com',
        'customer',
        [
            'customer.portal.view',
            'customer.portal.tickets.view',
            'customer.portal.invoices.view',
            'customer.portal.marketplace.view',
            'customer.portal.pay',
        ]
    );

    $customer = sec_create_customer($company, $branch, 'CP01', $customerUser->email);
    $device = sec_create_device($company, $branch, $customer, 'CP01');

    Ticket::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'customer_id' => $customer->id,
        'device_id' => $device->id,
        'ticket_number' => 'TK-CUSTOMER-01',
        'status' => 'ASSIGNED',
        'priority' => 'MEDIUM',
        'channel' => 'WEB',
        'reported_problem' => 'Equipo no enciende.',
        'received_at' => now(),
    ]);

    Product::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'sku' => 'PR-CUSTOMER-01',
        'name' => 'Bateria premium',
        'category' => 'PART',
        'sale_price' => 120,
        'status' => 'ACTIVE',
    ]);

    ServiceContract::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'name' => 'Diagnostico express',
        'status' => 'active',
    ]);

    $this->post('/login', [
        'email' => $customerUser->email,
        'password' => 'Secret123!',
    ])->assertRedirect(route('portal.customer.dashboard'));

    $this->actingAs($customerUser)
        ->get(route('portal.customer.dashboard'))
        ->assertOk()
        ->assertSee('TK-CUSTOMER-01')
        ->assertSee('Marketplace');
});

it('prevents customer from viewing another customer ticket and invoice', function (): void {
    $company = sec_create_company('Customer Isolation Co', 'customer-isolation-co');
    $branch = sec_create_branch($company, 'CI01');

    $userA = sec_create_user(
        $company,
        'customer-a@example.com',
        'customer',
        ['customer.portal.tickets.view', 'customer.portal.invoices.view']
    );
    $userB = sec_create_user(
        $company,
        'customer-b@example.com',
        'customer',
        ['customer.portal.tickets.view', 'customer.portal.invoices.view']
    );

    $customerA = sec_create_customer($company, $branch, 'CIA', $userA->email);
    $customerB = sec_create_customer($company, $branch, 'CIB', $userB->email);

    $deviceA = sec_create_device($company, $branch, $customerA, 'CIA');
    $ticketA = sec_create_ticket($company, $branch, $customerA, $deviceA, 'CIA');
    $invoiceA = sec_create_invoice($company, $branch, $customerA, $ticketA, 'CIA');

    $this->actingAs($userB)
        ->get(route('portal.customer.tickets.show', $ticketA))
        ->assertForbidden();

    $this->actingAs($userB)
        ->get(route('portal.customer.invoices.show', $invoiceA))
        ->assertForbidden();
});

it('allows customer to pay own invoice and download receipt', function (): void {
    $company = sec_create_company('Customer Payments Co', 'customer-payments-co');
    $branch = sec_create_branch($company, 'CPY1');

    $customerUser = sec_create_user(
        $company,
        'customer-pay@example.com',
        'customer',
        ['customer.portal.invoices.view', 'customer.portal.pay']
    );

    $customer = sec_create_customer($company, $branch, 'CPY1', $customerUser->email);
    $device = sec_create_device($company, $branch, $customer, 'CPY1');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'CPY1');
    $invoice = sec_create_invoice($company, $branch, $customer, $ticket, 'CPY1');

    $this->actingAs($customerUser)
        ->post(route('portal.customer.invoices.pay', $invoice), [
            'payment_method' => 'CARD',
            'amount' => 50,
            'reference' => 'TEST-REF-001',
        ])
        ->assertRedirect();

    $payment = Payment::query()
        ->where('invoice_id', $invoice->id)
        ->latest()
        ->first();

    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::COMPLETED);
    expect($invoice->fresh()->status)->toBe('partially_paid');

    $this->actingAs($customerUser)
        ->get(route('portal.customer.payments.receipt', $payment))
        ->assertOk()
        ->assertHeader('Content-Disposition');
});

it('allows customer to approve and reject own pending quote', function (): void {
    $company = sec_create_company('Customer Quotes Co', 'customer-quotes-co');
    $branch = sec_create_branch($company, 'CQ01');

    $customerUser = sec_create_user(
        $company,
        'customer-quote@example.com',
        'customer',
        ['customer.portal.tickets.view']
    );

    $customer = sec_create_customer($company, $branch, 'CQ01', $customerUser->email);
    $device = sec_create_device($company, $branch, $customer, 'CQ01');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'CQ01');

    $quote = Quote::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'ticket_id' => $ticket->id,
        'diagnostic_id' => \App\Domains\Diagnostics\Models\Diagnostic::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'ticket_id' => $ticket->id,
            'technician_id' => sec_create_user($company, 'diag-tech@example.com', 'technician', ['diagnostics.create'])->id,
            'diagnostic_code' => 'DG-CQ01',
            'status' => 'COMPLETED',
            'reported_problem' => 'No enciende',
            'diagnostic_result' => 'Falla fuente',
            'recommended_solution' => 'Cambio fuente',
            'estimated_cost' => 100,
            'estimated_hours' => 1,
        ])->id,
        'quote_number' => 'QT-CQ-01',
        'status' => 'PENDING_APPROVAL',
        'subtotal' => 100,
        'tax' => 0,
        'discount' => 0,
        'total' => 100,
    ]);

    $this->actingAs($customerUser)
        ->post(route('portal.customer.quotes.approve', $quote))
        ->assertRedirect();

    expect($quote->fresh()->status)->toBe('APPROVED');
    expect($ticket->fresh()->status)->toBe('APPROVED');

    $quoteForReject = Quote::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'ticket_id' => $ticket->id,
        'diagnostic_id' => $quote->diagnostic_id,
        'quote_number' => 'QT-CQ-02',
        'status' => 'PENDING_APPROVAL',
        'subtotal' => 120,
        'tax' => 0,
        'discount' => 0,
        'total' => 120,
    ]);

    $this->actingAs($customerUser)
        ->post(route('portal.customer.quotes.reject', $quoteForReject))
        ->assertRedirect();

    expect($quoteForReject->fresh()->status)->toBe('REJECTED');
    expect($ticket->fresh()->status)->toBe('WAITING_QUOTE');
});

it('forbids customer from approving quote belonging to another customer/company', function (): void {
    $companyA = sec_create_company('Customer Quote Guard A', 'customer-quote-guard-a');
    $branchA = sec_create_branch($companyA, 'CQA1');
    $userA = sec_create_user(
        $companyA,
        'customer-guard-a@example.com',
        'customer',
        ['customer.portal.tickets.view']
    );
    $customerA = sec_create_customer($companyA, $branchA, 'CQA1', $userA->email);
    $deviceA = sec_create_device($companyA, $branchA, $customerA, 'CQA1');
    $ticketA = sec_create_ticket($companyA, $branchA, $customerA, $deviceA, 'CQA1');
    $diagnosticA = \App\Domains\Diagnostics\Models\Diagnostic::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $companyA->id,
        'branch_id' => $branchA->id,
        'ticket_id' => $ticketA->id,
        'technician_id' => sec_create_user($companyA, 'diag-guard-a@example.com', 'technician', ['diagnostics.create'])->id,
        'diagnostic_code' => 'DG-CQA1',
        'status' => 'COMPLETED',
        'reported_problem' => 'No enciende',
        'diagnostic_result' => 'Fuente dañada',
        'recommended_solution' => 'Reemplazo',
        'estimated_cost' => 90,
        'estimated_hours' => 1,
    ]);
    $quoteA = Quote::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $companyA->id,
        'branch_id' => $branchA->id,
        'ticket_id' => $ticketA->id,
        'diagnostic_id' => $diagnosticA->id,
        'quote_number' => 'QT-CQ-GUARD-01',
        'status' => 'PENDING_APPROVAL',
        'subtotal' => 90,
        'tax' => 0,
        'discount' => 0,
        'total' => 90,
    ]);

    $companyB = sec_create_company('Customer Quote Guard B', 'customer-quote-guard-b');
    $branchB = sec_create_branch($companyB, 'CQB1');
    $userB = sec_create_user(
        $companyB,
        'customer-guard-b@example.com',
        'customer',
        ['customer.portal.tickets.view']
    );
    sec_create_customer($companyB, $branchB, 'CQB1', $userB->email);

    $this->actingAs($userB)
        ->post(route('portal.customer.quotes.approve', $quoteA))
        ->assertForbidden();
});
