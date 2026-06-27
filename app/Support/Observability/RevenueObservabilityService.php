<?php

declare(strict_types=1);

namespace App\Support\Observability;

use Carbon\Carbon;
use App\Domains\Payments\Models\Payment;
use App\Domains\Subscriptions\Models\Subscription;

class RevenueObservabilityService
{
    public function snapshot(): array
    {
        $now = now();
        $window24h = $now->copy()->subDay();
        $window30d = $now->copy()->subDays(30);

        $completed24h = Payment::withoutGlobalScopes()
            ->where('status', Payment::COMPLETED)
            ->where('updated_at', '>=', $window24h)
            ->count();

        $failed24h = Payment::withoutGlobalScopes()
            ->where('status', Payment::FAILED)
            ->where('updated_at', '>=', $window24h)
            ->count();

        $cancelled24h = Payment::withoutGlobalScopes()
            ->where('status', Payment::CANCELLED)
            ->where('updated_at', '>=', $window24h)
            ->count();

        $processed24h = $completed24h + $failed24h + $cancelled24h;
        $successRate24h = $processed24h > 0
            ? round(($completed24h / $processed24h) * 100, 2)
            : 100.0;

        $pendingStaleMinutes = (int) config('observability.pending_online_stale_minutes', 30);
        $pendingOnlineStale = Payment::withoutGlobalScopes()
            ->where('status', Payment::PENDING)
            ->whereIn('payment_method', ['STRIPE', 'MERCADOPAGO'])
            ->where('created_at', '<=', Carbon::now()->subMinutes($pendingStaleMinutes))
            ->count();

        $activeSubscriptions = Subscription::withoutGlobalScopes()
            ->whereIn('status', ['active', 'trial'])
            ->count();

        $pastDueSubscriptions = Subscription::withoutGlobalScopes()
            ->where('status', 'past_due')
            ->count();

        $cancelled30d = Subscription::withoutGlobalScopes()
            ->where('status', 'cancelled')
            ->where('cancelled_at', '>=', $window30d)
            ->count();

        $subscriptionBase = max($activeSubscriptions + $pastDueSubscriptions + $cancelled30d, 1);
        $churn30d = round(($cancelled30d / $subscriptionBase) * 100, 2);

        $mrr = (float) Subscription::withoutGlobalScopes()
            ->whereIn('status', ['active', 'trial'])
            ->get()
            ->sum(function (Subscription $subscription): float {
                $amount = (float) $subscription->amount;
                return $subscription->billing_cycle === 'yearly'
                    ? round($amount / 12, 2)
                    : $amount;
            });

        $arr = round($mrr * 12, 2);

        $alerts = $this->buildAlerts(
            $successRate24h,
            $failed24h,
            $pendingOnlineStale,
            $pastDueSubscriptions,
            $churn30d
        );

        return [
            'payments' => [
                'completed_24h' => $completed24h,
                'failed_24h' => $failed24h,
                'cancelled_24h' => $cancelled24h,
                'processed_24h' => $processed24h,
                'success_rate_24h' => $successRate24h,
                'pending_online_stale' => $pendingOnlineStale,
            ],
            'subscriptions' => [
                'active' => $activeSubscriptions,
                'past_due' => $pastDueSubscriptions,
                'cancelled_30d' => $cancelled30d,
                'churn_30d' => $churn30d,
            ],
            'revenue' => [
                'mrr' => round($mrr, 2),
                'arr' => $arr,
            ],
            'alerts' => $alerts,
            'overall_status' => $this->overallStatus($alerts),
            'generated_at' => $now->toIso8601String(),
        ];
    }

    private function buildAlerts(
        float $successRate24h,
        int $failed24h,
        int $pendingOnlineStale,
        int $pastDueSubscriptions,
        float $churn30d
    ): array {
        $minSuccessRate = (float) config('observability.payment_success_rate_min', 97);
        $failedAlert = (int) config('observability.payment_failed_24h_alert', 5);
        $pendingAlert = (int) config('observability.pending_online_alert', 10);
        $pastDueAlert = (int) config('observability.subscriptions_past_due_alert', 5);
        $churnMax = (float) config('observability.subscriptions_churn_30d_max', 5);

        return [
            [
                'name' => 'Payment Success Rate 24h',
                'value' => $successRate24h.'%',
                'target' => '>='.$minSuccessRate.'%',
                'severity' => $successRate24h < $minSuccessRate ? 'HIGH' : 'OK',
            ],
            [
                'name' => 'Failed Payments 24h',
                'value' => (string) $failed24h,
                'target' => '<'.$failedAlert,
                'severity' => $failed24h >= $failedAlert ? 'HIGH' : 'OK',
            ],
            [
                'name' => 'Pending Online Payments (stale)',
                'value' => (string) $pendingOnlineStale,
                'target' => '<'.$pendingAlert,
                'severity' => $pendingOnlineStale >= $pendingAlert ? 'MEDIUM' : 'OK',
            ],
            [
                'name' => 'Past Due Subscriptions',
                'value' => (string) $pastDueSubscriptions,
                'target' => '<'.$pastDueAlert,
                'severity' => $pastDueSubscriptions >= $pastDueAlert ? 'HIGH' : 'OK',
            ],
            [
                'name' => 'Subscription Churn 30d',
                'value' => $churn30d.'%',
                'target' => '<='.$churnMax.'%',
                'severity' => $churn30d > $churnMax ? 'MEDIUM' : 'OK',
            ],
        ];
    }

    private function overallStatus(array $alerts): string
    {
        $priorities = [
            'OK' => 0,
            'MEDIUM' => 1,
            'HIGH' => 2,
        ];

        $max = 0;
        foreach ($alerts as $alert) {
            $severity = (string) ($alert['severity'] ?? 'OK');
            $max = max($max, $priorities[$severity] ?? 0);
        }

        return array_search($max, $priorities, true) ?: 'OK';
    }
}
