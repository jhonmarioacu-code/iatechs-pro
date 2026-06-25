<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
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
