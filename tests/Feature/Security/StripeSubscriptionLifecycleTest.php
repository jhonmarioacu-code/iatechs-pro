<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Plans\Models\Plan;
use App\Domains\Subscriptions\Models\Subscription;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('creates stripe checkout for an existing subscription', function (): void {
    config()->set('services.stripe', [
        'key' => 'pk_test_sub_123',
        'secret' => 'sk_test_sub_123',
        'webhook_secret' => 'whsec_sub_123',
    ]);

    Http::fake([
        'https://api.stripe.com/v1/checkout/sessions' => Http::response([
            'id' => 'cs_sub_test_123',
            'url' => 'https://checkout.stripe.com/pay/cs_sub_test_123',
        ], 200),
    ]);

    $company = sec_create_company('Sub Checkout Co', 'sub-checkout-co');
    $user = sec_create_user(
        $company,
        'sub-checkout-owner@example.com',
        'owner',
        ['subscriptions.update']
    );

    $plan = sec_create_plan('sub-checkout-plan', 99, 999);

    $subscription = Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 99,
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addMonth()->toDateString(),
        'status' => 'trial',
    ]);

    $this->actingAs($user)
        ->postJson(route('subscriptions.checkout', $subscription), [
            'provider' => 'stripe',
        ])
        ->assertOk()
        ->assertJson([
            'success' => true,
            'provider' => 'STRIPE',
            'checkout_url' => 'https://checkout.stripe.com/pay/cs_sub_test_123',
            'checkout_session_id' => 'cs_sub_test_123',
        ]);

    expect($subscription->fresh()->payment_provider)->toBe('STRIPE');
});

it('syncs subscription as active when stripe checkout session completed webhook is received', function (): void {
    config()->set('services.stripe', [
        'key' => 'pk_test_sub_123',
        'secret' => 'sk_test_sub_123',
        'webhook_secret' => 'whsec_sub_123',
    ]);

    Http::fake([
        'https://api.stripe.com/v1/subscriptions/sub_test_123' => Http::response([
            'id' => 'sub_test_123',
            'status' => 'active',
            'current_period_start' => 1761955200,
            'current_period_end' => 1764547200,
        ], 200),
    ]);

    $company = sec_create_company('Sub Webhook Co', 'sub-webhook-co');
    $plan = sec_create_plan('sub-webhook-plan', 120, 1200);

    $subscription = Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 120,
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addMonth()->toDateString(),
        'status' => 'trial',
    ]);

    $payload = json_encode([
        'id' => 'evt_sub_checkout_completed',
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'mode' => 'subscription',
                'subscription' => 'sub_test_123',
                'metadata' => [
                    'subscription_id' => (string) $subscription->id,
                ],
            ],
        ],
    ], JSON_THROW_ON_ERROR);

    $timestamp = (string) time();
    $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_sub_123');

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

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'handled' => true,
        ]);

    $fresh = $subscription->fresh();
    expect($fresh->payment_provider)->toBe('STRIPE');
    expect($fresh->external_subscription_id)->toBe('sub_test_123');
    expect($fresh->status)->toBe('active');
});

it('marks subscription as past_due when stripe subscription update webhook reports past_due', function (): void {
    config()->set('services.stripe', [
        'key' => 'pk_test_sub_123',
        'secret' => 'sk_test_sub_123',
        'webhook_secret' => 'whsec_sub_123',
    ]);

    $company = sec_create_company('Sub Past Due Co', 'sub-past-due-co');
    $plan = sec_create_plan('sub-past-due-plan', 80, 800);

    $subscription = Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 80,
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addMonth()->toDateString(),
        'status' => 'active',
        'payment_provider' => 'STRIPE',
        'external_subscription_id' => 'sub_past_due_123',
    ]);

    $payload = json_encode([
        'id' => 'evt_sub_updated',
        'type' => 'customer.subscription.updated',
        'data' => [
            'object' => [
                'id' => 'sub_past_due_123',
                'status' => 'past_due',
                'current_period_start' => 1761955200,
                'current_period_end' => 1764547200,
            ],
        ],
    ], JSON_THROW_ON_ERROR);

    $timestamp = (string) time();
    $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_sub_123');

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

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'handled' => true,
        ]);

    expect($subscription->fresh()->status)->toBe('past_due');
});

it('creates mercadopago checkout for an existing subscription', function (): void {
    config()->set('services.mercadopago', [
        'access_token' => 'mp_test_token',
        'public_key' => 'mp_test_public',
    ]);

    Http::fake([
        'https://api.mercadopago.com/preapproval' => Http::response([
            'id' => 'preapproval_test_123',
            'init_point' => 'https://www.mercadopago.com/checkout/v1/redirect?pref=preapproval_test_123',
        ], 200),
    ]);

    $company = sec_create_company('MP Checkout Co', 'mp-checkout-co');
    $user = sec_create_user(
        $company,
        'mp-checkout-owner@example.com',
        'owner',
        ['subscriptions.update']
    );

    $plan = sec_create_plan('mp-checkout-plan', 130, 1300);

    $subscription = Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 130,
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addMonth()->toDateString(),
        'status' => 'trial',
    ]);

    $this->actingAs($user)
        ->postJson(route('subscriptions.checkout', $subscription), [
            'provider' => 'mercadopago',
        ])
        ->assertOk()
        ->assertJson([
            'success' => true,
            'provider' => 'MERCADOPAGO',
            'checkout_url' => 'https://www.mercadopago.com/checkout/v1/redirect?pref=preapproval_test_123',
            'external_subscription_id' => 'preapproval_test_123',
        ]);

    $fresh = $subscription->fresh();
    expect($fresh->payment_provider)->toBe('MERCADOPAGO');
    expect($fresh->external_subscription_id)->toBe('preapproval_test_123');
});

it('syncs mercadopago subscription status through webhook', function (): void {
    config()->set('services.mercadopago', [
        'access_token' => 'mp_test_token',
        'public_key' => 'mp_test_public',
    ]);

    $company = sec_create_company('MP Webhook Co', 'mp-webhook-co');
    $plan = sec_create_plan('mp-webhook-plan', 110, 1100);

    $subscription = Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 110,
        'starts_at' => now()->toDateString(),
        'ends_at' => now()->addMonth()->toDateString(),
        'status' => 'trial',
    ]);

    Http::fake([
        'https://api.mercadopago.com/preapproval/preapproval_mp_123' => Http::response([
            'id' => 'preapproval_mp_123',
            'status' => 'authorized',
            'external_reference' => 'subscription:'.$subscription->id,
            'date_created' => '2026-06-01T00:00:00.000-04:00',
            'next_payment_date' => '2026-07-01T00:00:00.000-04:00',
            'date_last_updated' => '2026-06-27T00:00:00.000-04:00',
        ], 200),
    ]);

    $payload = [
        'type' => 'subscription_preapproval',
        'data' => [
            'id' => 'preapproval_mp_123',
        ],
    ];

    $response = $this->postJson('/api/webhooks/mercadopago', $payload);

    $response->assertOk()->assertJson([
        'success' => true,
        'handled' => true,
    ]);

    $fresh = $subscription->fresh();
    expect($fresh->payment_provider)->toBe('MERCADOPAGO');
    expect($fresh->external_subscription_id)->toBe('preapproval_mp_123');
    expect($fresh->status)->toBe('active');
});
