<?php

declare(strict_types=1);

namespace App\Domains\Payments\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Payments\Models\Payment;
use App\Domains\Shared\Exceptions\DomainOperationException;

class OnlinePaymentGatewayService
{
    public function createCheckout(
        Payment $payment,
        Invoice $invoice
    ): array {
        return match ($payment->payment_method) {
            'STRIPE' => $this->createStripeCheckout($payment, $invoice),
            'MERCADOPAGO' => $this->createMercadoPagoCheckout($payment, $invoice),
            default => throw new DomainOperationException('Metodo de pago online no soportado.'),
        };
    }

    private function createStripeCheckout(
        Payment $payment,
        Invoice $invoice
    ): array {
        $secret = (string) config('services.stripe.secret', '');
        $key = (string) config('services.stripe.key', '');

        if ($secret === '' || $key === '') {
            throw new DomainOperationException('Stripe no esta configurado.');
        }

        $currency = strtolower((string) $payment->currency);
        $amountCents = (int) round((float) $payment->amount * 100);

        $response = Http::asForm()
            ->withBasicAuth($secret, '')
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'mode' => 'payment',
                'success_url' => route('portal.customer.invoices.show', $invoice).'?payment=stripe_success',
                'cancel_url' => route('portal.customer.invoices.show', $invoice).'?payment=stripe_cancelled',
                'client_reference_id' => (string) $payment->id,
                'metadata[payment_id]' => (string) $payment->id,
                'line_items[0][price_data][currency]' => $currency,
                'line_items[0][price_data][unit_amount]' => $amountCents,
                'line_items[0][price_data][product_data][name]' => 'Pago factura '.$invoice->invoice_number,
                'line_items[0][quantity]' => 1,
            ]);

        if ($response->failed()) {
            throw new DomainOperationException('Stripe rechazo la creacion de checkout.');
        }

        $sessionId = (string) $response->json('id', '');
        $checkoutUrl = (string) $response->json('url', '');

        if ($sessionId === '' || $checkoutUrl === '') {
            throw new DomainOperationException('Stripe no devolvio datos de checkout validos.');
        }

        return [
            'provider' => 'STRIPE',
            'external_id' => $sessionId,
            'checkout_url' => $checkoutUrl,
        ];
    }

    private function createMercadoPagoCheckout(
        Payment $payment,
        Invoice $invoice
    ): array {
        $token = (string) config('services.mercadopago.access_token', '');

        if ($token === '') {
            throw new DomainOperationException('MercadoPago no esta configurado.');
        }

        $response = Http::withToken($token)
            ->post('https://api.mercadopago.com/checkout/preferences', [
                'items' => [[
                    'title' => 'Pago factura '.$invoice->invoice_number,
                    'quantity' => 1,
                    'unit_price' => (float) $payment->amount,
                    'currency_id' => strtoupper((string) $payment->currency),
                ]],
                'external_reference' => 'payment:'.$payment->id,
                'notification_url' => route('webhooks.mercadopago'),
                'back_urls' => [
                    'success' => route('portal.customer.invoices.show', $invoice).'?payment=mp_success',
                    'failure' => route('portal.customer.invoices.show', $invoice).'?payment=mp_failed',
                    'pending' => route('portal.customer.invoices.show', $invoice).'?payment=mp_pending',
                ],
                'auto_return' => 'approved',
            ]);

        if ($response->failed()) {
            throw new DomainOperationException('MercadoPago rechazo la creacion de checkout.');
        }

        $preferenceId = (string) $response->json('id', '');
        $checkoutUrl = (string) Arr::first(array_filter([
            (string) $response->json('init_point', ''),
            (string) $response->json('sandbox_init_point', ''),
        ]));

        if ($preferenceId === '' || $checkoutUrl === '') {
            throw new DomainOperationException('MercadoPago no devolvio datos de checkout validos.');
        }

        return [
            'provider' => 'MERCADOPAGO',
            'external_id' => $preferenceId,
            'checkout_url' => $checkoutUrl,
        ];
    }
}
