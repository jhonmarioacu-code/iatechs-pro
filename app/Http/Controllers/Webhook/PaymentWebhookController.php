<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domains\Payments\Services\PaymentWebhookService;
use App\Domains\Subscriptions\Services\SubscriptionWebhookService;

class PaymentWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentWebhookService $paymentWebhookService,
        private readonly SubscriptionWebhookService $subscriptionWebhookService
    ) {}

    public function stripe(Request $request): JsonResponse
    {
        $payload = (string) $request->getContent();
        $signature = (string) $request->header('Stripe-Signature', '');
        $secret = (string) config('services.stripe.webhook_secret', '');

        if ($secret === '' || !$this->isValidStripeSignature($payload, $signature, $secret)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Stripe signature.',
            ], 400);
        }

        /** @var array<string, mixed> $event */
        $event = (array) $request->json()->all();
        $handledPayments = $this->paymentWebhookService->handleStripeEvent($event);
        $handledSubscriptions = $this->subscriptionWebhookService->handleStripeEvent($event);
        $handled = $handledPayments || $handledSubscriptions;

        return response()->json([
            'success' => true,
            'handled' => $handled,
        ]);
    }

    public function mercadopago(Request $request): JsonResponse
    {
        $payload = (array) $request->json()->all();
        if ($payload === []) {
            $payload = $request->all();
        }

        $handledPayments = $this->paymentWebhookService->handleMercadoPagoNotification($payload);
        $handledSubscriptions = $this->subscriptionWebhookService->handleMercadoPagoNotification($payload);
        $handled = $handledPayments || $handledSubscriptions;

        return response()->json([
            'success' => true,
            'handled' => $handled,
        ]);
    }

    private function isValidStripeSignature(
        string $payload,
        string $signatureHeader,
        string $secret
    ): bool {
        if ($signatureHeader === '') {
            return false;
        }

        $pairs = [];
        foreach (explode(',', $signatureHeader) as $segment) {
            [$key, $value] = array_pad(explode('=', trim($segment), 2), 2, null);
            if ($key !== null && $value !== null) {
                $pairs[$key] = $value;
            }
        }

        $timestamp = $pairs['t'] ?? null;
        $signature = $pairs['v1'] ?? null;

        if ($timestamp === null || $signature === null) {
            return false;
        }

        $signedPayload = $timestamp.'.'.$payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($expected, $signature);
    }
}
