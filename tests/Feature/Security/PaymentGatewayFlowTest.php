<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Payments\Models\Payment;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('starts stripe checkout for customer invoice payments', function (): void {
    config()->set('services.stripe', [
        'key' => 'pk_test_123',
        'secret' => 'sk_test_123',
        'webhook_secret' => 'whsec_test_123',
    ]);

    Http::fake([
        'https://api.stripe.com/v1/checkout/sessions' => Http::response([
            'id' => 'cs_test_123',
            'url' => 'https://checkout.stripe.com/pay/cs_test_123',
        ], 200),
    ]);

    $company = sec_create_company('Stripe Co', 'stripe-co');
    $branch = sec_create_branch($company, 'ST01');

    $customerUser = sec_create_user(
        $company,
        'stripe-customer@example.com',
        'customer',
        ['customer.portal.invoices.view', 'customer.portal.pay']
    );

    $customer = sec_create_customer($company, $branch, 'ST01', $customerUser->email);
    $device = sec_create_device($company, $branch, $customer, 'ST01');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'ST01');
    $invoice = sec_create_invoice($company, $branch, $customer, $ticket, 'ST01');

    $this->actingAs($customerUser)
        ->post(route('portal.customer.invoices.pay', $invoice), [
            'payment_method' => 'STRIPE',
            'amount' => 50,
            'reference' => 'STRIPE-TEST-001',
        ])
        ->assertRedirect('https://checkout.stripe.com/pay/cs_test_123');

    $payment = Payment::query()
        ->where('invoice_id', $invoice->id)
        ->latest()
        ->first();

    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::PENDING);
    expect($payment->external_transaction_id)->toBe('cs_test_123');
    expect($invoice->fresh()->status)->toBe('issued');
});

it('completes pending payment through stripe webhook with valid signature', function (): void {
    config()->set('services.stripe', [
        'key' => 'pk_test_123',
        'secret' => 'sk_test_123',
        'webhook_secret' => 'whsec_test_123',
    ]);

    $company = sec_create_company('Stripe Webhook Co', 'stripe-webhook-co');
    $branch = sec_create_branch($company, 'SW01');
    $customerUser = sec_create_user($company, 'stripe-webhook-customer@example.com', 'customer');
    $customer = sec_create_customer($company, $branch, 'SW01', $customerUser->email);
    $device = sec_create_device($company, $branch, $customer, 'SW01');
    $ticket = sec_create_ticket($company, $branch, $customer, $device, 'SW01');
    $invoice = sec_create_invoice($company, $branch, $customer, $ticket, 'SW01');
    $payment = sec_create_payment($company, $branch, $customer, $invoice, $customerUser, 'SW01');

    $payment->update([
        'payment_method' => 'STRIPE',
        'amount' => 119,
        'status' => Payment::PENDING,
    ]);

    $payload = json_encode([
        'id' => 'evt_test_1',
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => 'cs_test_abc',
                'payment_intent' => 'pi_test_abc',
                'metadata' => [
                    'payment_id' => (string) $payment->id,
                ],
            ],
        ],
    ], JSON_THROW_ON_ERROR);

    $timestamp = (string) time();
    $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test_123');

    $response = $this->call(
        'POST',
        '/api/webhooks/stripe',
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Stripe-Signature' => "t={$timestamp},v1={$signature}",
        ],
        $payload
    );

    $response->assertOk();
    $response->assertJson(['success' => true, 'handled' => true]);

    expect($payment->fresh()->status)->toBe(Payment::COMPLETED);
    expect($payment->fresh()->external_transaction_id)->toBe('pi_test_abc');
    expect($invoice->fresh()->status)->toBe('paid');
});

it('rejects stripe webhook when signature is invalid', function (): void {
    config()->set('services.stripe', [
        'key' => 'pk_test_123',
        'secret' => 'sk_test_123',
        'webhook_secret' => 'whsec_test_123',
    ]);

    $payload = json_encode([
        'id' => 'evt_test_invalid',
        'type' => 'checkout.session.completed',
        'data' => ['object' => []],
    ], JSON_THROW_ON_ERROR);

    $response = $this->call(
        'POST',
        '/api/webhooks/stripe',
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Stripe-Signature' => 't=12345,v1=invalid',
        ],
        $payload
    );

    $response->assertStatus(400);
    $response->assertJson(['success' => false]);
});
