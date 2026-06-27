<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Services;

use Illuminate\Support\Facades\Http;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Shared\Exceptions\DomainOperationException;

class MercadoPagoSubscriptionGatewayService
{
    public function createCheckout(
        Subscription $subscription,
        string $payerEmail
    ): array {
        $token = (string) config('services.mercadopago.access_token', '');
        if ($token === '') {
            throw new DomainOperationException('MercadoPago no esta configurado para suscripciones.');
        }

        $frequency = $subscription->billing_cycle === 'yearly' ? 12 : 1;
        $frequencyType = 'months';
        $currency = strtoupper((string) config('services.mercadopago.currency', 'COP'));

        $payload = [
            'reason' => 'Suscripcion IAtechs Pro',
            'external_reference' => 'subscription:'.$subscription->id,
            'notification_url' => route('webhooks.mercadopago'),
            'back_url' => rtrim((string) config('app.url'), '/').'/portal/company?subscription=mercadopago_return',
            'auto_recurring' => [
                'frequency' => $frequency,
                'frequency_type' => $frequencyType,
                'transaction_amount' => (float) $subscription->amount,
                'currency_id' => $currency,
                'start_date' => now()->toIso8601String(),
            ],
        ];

        if ($payerEmail !== '') {
            $payload['payer_email'] = $payerEmail;
        }

        $response = Http::withToken($token)
            ->post('https://api.mercadopago.com/preapproval', $payload);

        if ($response->failed()) {
            throw new DomainOperationException('MercadoPago rechazo la creacion de suscripcion recurrente.');
        }

        $preapprovalId = (string) $response->json('id', '');
        $checkoutUrl = (string) $response->json('init_point', '');
        if ($checkoutUrl === '') {
            $checkoutUrl = (string) $response->json('sandbox_init_point', '');
        }

        if ($preapprovalId === '' || $checkoutUrl === '') {
            throw new DomainOperationException('MercadoPago no devolvio un checkout valido para suscripcion.');
        }

        return [
            'provider' => 'MERCADOPAGO',
            'checkout_url' => $checkoutUrl,
            'external_subscription_id' => $preapprovalId,
        ];
    }
}
