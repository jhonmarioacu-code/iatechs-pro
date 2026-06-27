<?php

declare(strict_types=1);

namespace App\Domains\Subscriptions\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\Domains\Subscriptions\Models\Subscription;

class SubscriptionWebhookService
{
    public function handleStripeEvent(array $event): bool
    {
        $type = (string) Arr::get($event, 'type', '');

        return match ($type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event),
            'customer.subscription.created', 'customer.subscription.updated', 'customer.subscription.deleted' => $this->handleSubscriptionState($event),
            'invoice.paid' => $this->handleInvoicePaid($event),
            'invoice.payment_failed' => $this->handleInvoicePaymentFailed($event),
            default => false,
        };
    }

    public function handleMercadoPagoNotification(array $payload): bool
    {
        if (!$this->looksLikeMercadoPagoSubscriptionEvent($payload)) {
            return false;
        }

        $externalSubscriptionId = (string) (
            Arr::get($payload, 'data.id')
            ?? Arr::get($payload, 'id')
            ?? ''
        );

        if ($externalSubscriptionId === '') {
            $resource = (string) Arr::get($payload, 'resource', '');
            if (str_contains($resource, '/preapproval/')) {
                $externalSubscriptionId = (string) basename($resource);
            }
        }

        if ($externalSubscriptionId === '') {
            return false;
        }

        $token = (string) config('services.mercadopago.access_token', '');
        if ($token === '') {
            return false;
        }

        $response = Http::withToken($token)
            ->get('https://api.mercadopago.com/preapproval/'.$externalSubscriptionId);

        if ($response->failed()) {
            return false;
        }

        $externalReference = (string) $response->json('external_reference', '');
        $internalId = $this->resolveInternalSubscriptionIdFromExternalReference($externalReference);

        $subscription = Subscription::withoutGlobalScopes()
            ->where('external_subscription_id', $externalSubscriptionId)
            ->first();

        if ($subscription === null && $internalId > 0) {
            $subscription = Subscription::withoutGlobalScopes()->find($internalId);
        }

        if ($subscription === null) {
            return false;
        }

        $status = $this->mapMercadoPagoStatus((string) $response->json('status', ''));
        $startsAt = $this->isoToDate($response->json('date_created'));
        $endsAt = $this->isoToDate($response->json('next_payment_date'));
        $cancelledAt = $this->isoToDateTime($response->json('date_last_updated'));

        $update = [
            'payment_provider' => 'MERCADOPAGO',
            'external_subscription_id' => $externalSubscriptionId,
            'status' => $status,
        ];

        if ($startsAt !== null) {
            $update['starts_at'] = $startsAt;
        }
        if ($endsAt !== null) {
            $update['ends_at'] = $endsAt;
        }
        if ($status === 'cancelled') {
            $update['cancelled_at'] = $cancelledAt ?? now();
        }

        $subscription->forceFill($update)->save();

        return true;
    }

    private function handleCheckoutCompleted(array $event): bool
    {
        $mode = (string) Arr::get($event, 'data.object.mode', '');
        if ($mode !== 'subscription') {
            return false;
        }

        $internalId = (int) (
            Arr::get($event, 'data.object.metadata.subscription_id')
            ?? Arr::get($event, 'data.object.client_reference_id', 0)
        );

        $externalSubscriptionId = (string) Arr::get($event, 'data.object.subscription', '');

        if ($internalId <= 0 || $externalSubscriptionId === '') {
            return false;
        }

        $subscription = Subscription::withoutGlobalScopes()->find($internalId);
        if ($subscription === null) {
            return false;
        }

        $subscription->forceFill([
            'payment_provider' => 'STRIPE',
            'external_subscription_id' => $externalSubscriptionId,
        ])->save();

        $this->syncStripeSubscriptionState($subscription, $externalSubscriptionId);

        return true;
    }

    private function handleSubscriptionState(array $event): bool
    {
        $externalSubscriptionId = (string) Arr::get($event, 'data.object.id', '');
        if ($externalSubscriptionId === '') {
            return false;
        }

        $subscription = Subscription::withoutGlobalScopes()
            ->where('external_subscription_id', $externalSubscriptionId)
            ->first();

        if ($subscription === null) {
            $internalId = (int) Arr::get($event, 'data.object.metadata.subscription_id', 0);
            if ($internalId > 0) {
                $subscription = Subscription::withoutGlobalScopes()->find($internalId);
            }
        }

        if ($subscription === null) {
            return false;
        }

        $status = $this->mapStripeStatus((string) Arr::get($event, 'data.object.status', ''));
        $startsAt = $this->unixToDate(Arr::get($event, 'data.object.current_period_start'));
        $endsAt = $this->unixToDate(Arr::get($event, 'data.object.current_period_end'));
        $cancelledAt = $this->unixToDateTime(Arr::get($event, 'data.object.canceled_at'));

        $update = [
            'payment_provider' => 'STRIPE',
            'external_subscription_id' => $externalSubscriptionId,
            'status' => $status,
        ];

        if ($startsAt !== null) {
            $update['starts_at'] = $startsAt;
        }
        if ($endsAt !== null) {
            $update['ends_at'] = $endsAt;
        }
        if ($status === 'cancelled') {
            $update['cancelled_at'] = $cancelledAt ?? now();
        }

        $subscription->forceFill($update)->save();

        return true;
    }

    private function handleInvoicePaid(array $event): bool
    {
        $externalSubscriptionId = (string) Arr::get($event, 'data.object.subscription', '');
        if ($externalSubscriptionId === '') {
            return false;
        }

        $subscription = Subscription::withoutGlobalScopes()
            ->where('external_subscription_id', $externalSubscriptionId)
            ->first();

        if ($subscription === null) {
            return false;
        }

        $periodStart = Arr::get($event, 'data.object.lines.data.0.period.start');
        $periodEnd = Arr::get($event, 'data.object.lines.data.0.period.end');

        $update = [
            'status' => 'active',
        ];

        $startsAt = $this->unixToDate($periodStart);
        $endsAt = $this->unixToDate($periodEnd);

        if ($startsAt !== null) {
            $update['starts_at'] = $startsAt;
        }
        if ($endsAt !== null) {
            $update['ends_at'] = $endsAt;
        }

        $subscription->forceFill($update)->save();

        return true;
    }

    private function handleInvoicePaymentFailed(array $event): bool
    {
        $externalSubscriptionId = (string) Arr::get($event, 'data.object.subscription', '');
        if ($externalSubscriptionId === '') {
            return false;
        }

        $subscription = Subscription::withoutGlobalScopes()
            ->where('external_subscription_id', $externalSubscriptionId)
            ->first();

        if ($subscription === null) {
            return false;
        }

        $subscription->forceFill([
            'status' => 'past_due',
        ])->save();

        return true;
    }

    private function syncStripeSubscriptionState(Subscription $subscription, string $externalSubscriptionId): void
    {
        $secret = (string) config('services.stripe.secret', '');
        if ($secret === '') {
            return;
        }

        $response = Http::withBasicAuth($secret, '')
            ->get('https://api.stripe.com/v1/subscriptions/'.$externalSubscriptionId);

        if ($response->failed()) {
            return;
        }

        $status = $this->mapStripeStatus((string) $response->json('status', ''));
        $startsAt = $this->unixToDate($response->json('current_period_start'));
        $endsAt = $this->unixToDate($response->json('current_period_end'));
        $cancelledAt = $this->unixToDateTime($response->json('canceled_at'));

        $update = [
            'status' => $status,
        ];

        if ($startsAt !== null) {
            $update['starts_at'] = $startsAt;
        }
        if ($endsAt !== null) {
            $update['ends_at'] = $endsAt;
        }
        if ($status === 'cancelled') {
            $update['cancelled_at'] = $cancelledAt ?? now();
        }

        $subscription->forceFill($update)->save();
    }

    private function mapStripeStatus(string $status): string
    {
        return match ($status) {
            'trialing' => 'trial',
            'active' => 'active',
            'past_due', 'unpaid', 'incomplete' => 'past_due',
            'canceled' => 'cancelled',
            'incomplete_expired' => 'expired',
            default => 'active',
        };
    }

    private function mapMercadoPagoStatus(string $status): string
    {
        return match ($status) {
            'authorized' => 'active',
            'pending' => 'trial',
            'paused' => 'past_due',
            'cancelled' => 'cancelled',
            default => 'active',
        };
    }

    private function looksLikeMercadoPagoSubscriptionEvent(array $payload): bool
    {
        $type = strtolower((string) Arr::get($payload, 'type', ''));
        $action = strtolower((string) Arr::get($payload, 'action', ''));
        $topic = strtolower((string) Arr::get($payload, 'topic', ''));
        $resource = strtolower((string) Arr::get($payload, 'resource', ''));

        return str_contains($type, 'subscription')
            || str_contains($type, 'preapproval')
            || str_contains($topic, 'subscription')
            || str_contains($topic, 'preapproval')
            || str_contains($action, 'subscription')
            || str_contains($action, 'preapproval')
            || str_contains($resource, '/preapproval/');
    }

    private function resolveInternalSubscriptionIdFromExternalReference(string $externalReference): int
    {
        if (!str_starts_with($externalReference, 'subscription:')) {
            return 0;
        }

        return (int) str_replace('subscription:', '', $externalReference);
    }

    private function unixToDate(mixed $value): ?string
    {
        if (!is_numeric($value)) {
            return null;
        }

        return Carbon::createFromTimestamp((int) $value)->toDateString();
    }

    private function unixToDateTime(mixed $value): ?string
    {
        if (!is_numeric($value)) {
            return null;
        }

        return Carbon::createFromTimestamp((int) $value)->toDateTimeString();
    }

    private function isoToDate(mixed $value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        return Carbon::parse($value)->toDateString();
    }

    private function isoToDateTime(mixed $value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        return Carbon::parse($value)->toDateTimeString();
    }
}
