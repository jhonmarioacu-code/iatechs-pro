<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Services;

use Illuminate\Support\Facades\Http;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Shared\Exceptions\DomainOperationException;

class StripeSubscriptionGatewayService
{
    public function createCheckout(
        Subscription $subscription,
        string $customerEmail
    ): array {
        $secret = (string) config('services.stripe.secret', '');
        $key = (string) config('services.stripe.key', '');

        if ($secret === '' || $key === '') {
            throw new DomainOperationException('Stripe no esta configurado para suscripciones.');
        }

        $interval = $subscription->billing_cycle === 'yearly' ? 'year' : 'month';
        $amountCents = (int) round((float) $subscription->amount * 100);
        $appUrl = rtrim((string) config('app.url'), '/');

        $successUrl = $appUrl.'/portal/company?subscription=stripe_success';
        $cancelUrl = $appUrl.'/portal/company?subscription=stripe_cancelled';

        $payload = [
            'mode' => 'subscription',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => (string) $subscription->id,
            'metadata[subscription_id]' => (string) $subscription->id,
            'subscription_data[metadata][subscription_id]' => (string) $subscription->id,
            'line_items[0][price_data][currency]' => 'cop',
            'line_items[0][price_data][unit_amount]' => $amountCents,
            'line_items[0][price_data][recurring][interval]' => $interval,
            'line_items[0][price_data][product_data][name]' => 'Suscripcion IAtechs Pro',
            'line_items[0][quantity]' => 1,
        ];

        if ($customerEmail !== '') {
            $payload['customer_email'] = $customerEmail;
        }

        $response = Http::asForm()
            ->withBasicAuth($secret, '')
            ->post('https://api.stripe.com/v1/checkout/sessions', $payload);

        if ($response->failed()) {
            throw new DomainOperationException('Stripe rechazo la creacion del checkout de suscripcion.');
        }

        $sessionId = (string) $response->json('id', '');
        $checkoutUrl = (string) $response->json('url', '');

        if ($sessionId === '' || $checkoutUrl === '') {
            throw new DomainOperationException('Stripe no devolvio un checkout valido para suscripcion.');
        }

        return [
            'provider' => 'STRIPE',
            'checkout_session_id' => $sessionId,
            'checkout_url' => $checkoutUrl,
        ];
    }
}
