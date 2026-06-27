<?php

declare(strict_types=1);

namespace App\Domains\Payments\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Payments\Models\Payment;

class PaymentWebhookService
{
    public function handleStripeEvent(array $event): bool
    {
        $type = (string) Arr::get($event, 'type', '');

        if ($type === 'checkout.session.completed') {
            $paymentId = (int) Arr::get($event, 'data.object.metadata.payment_id', 0);
            $externalId = (string) Arr::get($event, 'data.object.payment_intent', Arr::get($event, 'data.object.id', ''));

            return $this->completePayment($paymentId, $externalId);
        }

        if ($type === 'checkout.session.expired') {
            $paymentId = (int) Arr::get($event, 'data.object.metadata.payment_id', 0);

            return $this->cancelPayment($paymentId);
        }

        return false;
    }

    public function handleMercadoPagoNotification(array $payload): bool
    {
        $paymentGatewayId = (string) (
            Arr::get($payload, 'data.id')
            ?? Arr::get($payload, 'id')
            ?? Arr::get($payload, 'resource.id')
            ?? ''
        );

        if ($paymentGatewayId === '') {
            return false;
        }

        $token = (string) config('services.mercadopago.access_token', '');
        if ($token === '') {
            return false;
        }

        $response = Http::withToken($token)
            ->get('https://api.mercadopago.com/v1/payments/'.$paymentGatewayId);

        if ($response->failed()) {
            return false;
        }

        $status = (string) $response->json('status', '');
        $externalReference = (string) $response->json('external_reference', '');
        $paymentId = $this->resolvePaymentIdFromExternalReference($externalReference);

        if ($paymentId <= 0) {
            return false;
        }

        return match ($status) {
            'approved' => $this->completePayment($paymentId, $paymentGatewayId),
            'cancelled' => $this->cancelPayment($paymentId),
            'rejected' => $this->failPayment($paymentId),
            default => false,
        };
    }

    private function completePayment(int $paymentId, string $externalId): bool
    {
        if ($paymentId <= 0) {
            return false;
        }

        /** @var Payment|null $payment */
        $payment = Payment::withoutGlobalScopes()->find($paymentId);
        if ($payment === null) {
            return false;
        }

        if ($payment->status !== Payment::COMPLETED) {
            $payment->forceFill([
                'status' => Payment::COMPLETED,
                'paid_at' => now(),
                'external_transaction_id' => $externalId !== '' ? $externalId : $payment->external_transaction_id,
            ])->save();
        }

        $this->refreshInvoiceStatus((int) $payment->invoice_id);

        return true;
    }

    private function cancelPayment(int $paymentId): bool
    {
        if ($paymentId <= 0) {
            return false;
        }

        /** @var Payment|null $payment */
        $payment = Payment::withoutGlobalScopes()->find($paymentId);
        if ($payment === null) {
            return false;
        }

        if ($payment->status !== Payment::CANCELLED) {
            $payment->forceFill([
                'status' => Payment::CANCELLED,
                'paid_at' => null,
            ])->save();
        }

        $this->refreshInvoiceStatus((int) $payment->invoice_id);

        return true;
    }

    private function failPayment(int $paymentId): bool
    {
        if ($paymentId <= 0) {
            return false;
        }

        /** @var Payment|null $payment */
        $payment = Payment::withoutGlobalScopes()->find($paymentId);
        if ($payment === null) {
            return false;
        }

        if ($payment->status !== Payment::FAILED) {
            $payment->forceFill([
                'status' => Payment::FAILED,
                'paid_at' => null,
            ])->save();
        }

        $this->refreshInvoiceStatus((int) $payment->invoice_id);

        return true;
    }

    private function refreshInvoiceStatus(int $invoiceId): void
    {
        /** @var Invoice|null $invoice */
        $invoice = Invoice::withoutGlobalScopes()->find($invoiceId);
        if ($invoice === null) {
            return;
        }

        $paidAmount = (float) Payment::withoutGlobalScopes()
            ->where('invoice_id', $invoiceId)
            ->where('status', Payment::COMPLETED)
            ->sum('amount');

        if ($paidAmount >= (float) $invoice->total) {
            $invoice->forceFill([
                'status' => 'paid',
                'paid_at' => now(),
            ])->save();

            return;
        }

        if ($paidAmount > 0) {
            $invoice->forceFill([
                'status' => 'partially_paid',
                'paid_at' => null,
            ])->save();

            return;
        }

        $invoice->forceFill([
            'status' => 'issued',
            'paid_at' => null,
        ])->save();
    }

    private function resolvePaymentIdFromExternalReference(string $externalReference): int
    {
        if (!str_starts_with($externalReference, 'payment:')) {
            return 0;
        }

        return (int) str_replace('payment:', '', $externalReference);
    }
}
