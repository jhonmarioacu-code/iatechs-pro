<?php

declare(strict_types=1);

namespace App\Support\Observability;

use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Carbon;

class PrometheusMetricsExporter
{
    public function __construct(
        private readonly RevenueObservabilityService $revenueObservabilityService
    ) {}

    public function render(): string
    {
        $snapshot = $this->revenueObservabilityService->snapshot();
        $lines = [];

        $this->appendMetric($lines, 'iatechs_http_requests_total', 'Total HTTP requests processed.', 'counter', [
            ['value' => (int) Cache::get('metrics:http:requests_total', 0)],
        ]);

        $this->appendMetric($lines, 'iatechs_http_last_duration_milliseconds', 'Last observed request duration in milliseconds.', 'gauge', [
            ['value' => (int) Cache::get('metrics:http:last_duration_ms', 0)],
        ]);

        $lastSeenAt = Cache::get('metrics:http:last_seen_at');
        $this->appendMetric($lines, 'iatechs_http_last_seen_unix', 'Unix timestamp for last observed request.', 'gauge', [
            ['value' => $this->toUnixTimestamp($lastSeenAt)],
        ]);

        $statusCodes = [200, 401, 403, 404, 429, 500];
        $statusSamples = [];
        foreach ($statusCodes as $statusCode) {
            $statusSamples[] = [
                'labels' => ['status_code' => (string) $statusCode],
                'value' => (int) Cache::get("metrics:http:status:{$statusCode}", 0),
            ];
        }
        $this->appendMetric($lines, 'iatechs_http_status_responses_total', 'Total responses grouped by HTTP status code.', 'counter', $statusSamples);

        $this->appendMetric($lines, 'iatechs_queue_pending_jobs', 'Pending jobs count on default redis queue.', 'gauge', [
            ['value' => $this->queueSize()],
        ]);

        $this->appendMetric($lines, 'iatechs_failed_jobs_total', 'Total failed jobs recorded in failed_jobs table.', 'gauge', [
            ['value' => $this->failedJobsCount()],
        ]);

        $payments = (array) ($snapshot['payments'] ?? []);
        $subscriptions = (array) ($snapshot['subscriptions'] ?? []);
        $revenue = (array) ($snapshot['revenue'] ?? []);

        $paymentSuccessPercent = (float) ($payments['success_rate_24h'] ?? 100.0);
        $subscriptionChurnPercent = (float) ($subscriptions['churn_30d'] ?? 0.0);

        $this->appendMetric($lines, 'iatechs_payments_completed_24h_total', 'Completed payments in last 24h.', 'gauge', [
            ['value' => (int) ($payments['completed_24h'] ?? 0)],
        ]);
        $this->appendMetric($lines, 'iatechs_payments_failed_24h_total', 'Failed payments in last 24h.', 'gauge', [
            ['value' => (int) ($payments['failed_24h'] ?? 0)],
        ]);
        $this->appendMetric($lines, 'iatechs_payments_cancelled_24h_total', 'Cancelled payments in last 24h.', 'gauge', [
            ['value' => (int) ($payments['cancelled_24h'] ?? 0)],
        ]);
        $this->appendMetric($lines, 'iatechs_payments_processed_24h_total', 'Processed payments in last 24h.', 'gauge', [
            ['value' => (int) ($payments['processed_24h'] ?? 0)],
        ]);
        $this->appendMetric($lines, 'iatechs_payment_success_rate_24h_percent', 'Payment success rate over the last 24h, in percent.', 'gauge', [
            ['value' => $paymentSuccessPercent],
        ]);
        $this->appendMetric($lines, 'iatechs_payment_success_rate_24h_ratio', 'Payment success rate over the last 24h, 0-1 ratio.', 'gauge', [
            ['value' => round($paymentSuccessPercent / 100, 6)],
        ]);
        $this->appendMetric($lines, 'iatechs_payments_pending_online_stale', 'Pending online payments older than configured threshold.', 'gauge', [
            ['value' => (int) ($payments['pending_online_stale'] ?? 0)],
        ]);

        $this->appendMetric($lines, 'iatechs_subscriptions_active', 'Active or trial subscriptions.', 'gauge', [
            ['value' => (int) ($subscriptions['active'] ?? 0)],
        ]);
        $this->appendMetric($lines, 'iatechs_subscriptions_past_due', 'Past due subscriptions.', 'gauge', [
            ['value' => (int) ($subscriptions['past_due'] ?? 0)],
        ]);
        $this->appendMetric($lines, 'iatechs_subscriptions_cancelled_30d_total', 'Cancelled subscriptions in last 30 days.', 'gauge', [
            ['value' => (int) ($subscriptions['cancelled_30d'] ?? 0)],
        ]);
        $this->appendMetric($lines, 'iatechs_subscription_churn_30d_percent', 'Subscription churn over last 30 days in percent.', 'gauge', [
            ['value' => $subscriptionChurnPercent],
        ]);
        $this->appendMetric($lines, 'iatechs_subscription_churn_30d_ratio', 'Subscription churn over last 30 days as 0-1 ratio.', 'gauge', [
            ['value' => round($subscriptionChurnPercent / 100, 6)],
        ]);

        $this->appendMetric($lines, 'iatechs_revenue_mrr', 'Monthly recurring revenue.', 'gauge', [
            ['value' => (float) ($revenue['mrr'] ?? 0)],
        ]);
        $this->appendMetric($lines, 'iatechs_revenue_arr', 'Annual recurring revenue.', 'gauge', [
            ['value' => (float) ($revenue['arr'] ?? 0)],
        ]);

        $overallStatus = (string) ($snapshot['overall_status'] ?? 'OK');
        $generatedAt = (string) ($snapshot['generated_at'] ?? '');
        $this->appendMetric($lines, 'iatechs_observability_overall_status', 'Overall observability severity (OK=0, MEDIUM=1, HIGH=2).', 'gauge', [
            ['value' => $this->severityToValue($overallStatus)],
        ]);
        $this->appendMetric($lines, 'iatechs_observability_generated_at_unix', 'Unix timestamp of observability snapshot generation.', 'gauge', [
            ['value' => $this->toUnixTimestamp($generatedAt)],
        ]);

        $alertSamples = [];
        $alertCounters = [
            'OK' => 0,
            'MEDIUM' => 0,
            'HIGH' => 0,
        ];
        foreach ((array) ($snapshot['alerts'] ?? []) as $alert) {
            $severity = strtoupper((string) ($alert['severity'] ?? 'OK'));
            if (! array_key_exists($severity, $alertCounters)) {
                $severity = 'OK';
            }
            $alertCounters[$severity]++;

            $alertSamples[] = [
                'labels' => [
                    'alert' => (string) ($alert['name'] ?? 'unknown'),
                    'severity' => $severity,
                    'target' => (string) ($alert['target'] ?? ''),
                    'current' => (string) ($alert['value'] ?? ''),
                ],
                'value' => $this->severityToValue($severity),
            ];
        }
        $this->appendMetric($lines, 'iatechs_observability_alert_severity', 'Severity per observability alert (OK=0, MEDIUM=1, HIGH=2).', 'gauge', $alertSamples);

        $this->appendMetric($lines, 'iatechs_observability_alerts_total', 'Number of observability alerts by severity.', 'gauge', [
            ['labels' => ['severity' => 'OK'], 'value' => $alertCounters['OK']],
            ['labels' => ['severity' => 'MEDIUM'], 'value' => $alertCounters['MEDIUM']],
            ['labels' => ['severity' => 'HIGH'], 'value' => $alertCounters['HIGH']],
        ]);

        $this->appendMetric($lines, 'iatechs_observability_threshold_payment_success_rate_min_percent', 'Configured minimum payment success rate (24h) in percent.', 'gauge', [
            ['value' => (float) config('observability.payment_success_rate_min', 97)],
        ]);
        $this->appendMetric($lines, 'iatechs_observability_threshold_payment_failed_24h_max', 'Configured failed payments alert threshold over 24h.', 'gauge', [
            ['value' => (int) config('observability.payment_failed_24h_alert', 5)],
        ]);
        $this->appendMetric($lines, 'iatechs_observability_threshold_pending_online_stale_max', 'Configured stale pending online payments alert threshold.', 'gauge', [
            ['value' => (int) config('observability.pending_online_alert', 10)],
        ]);
        $this->appendMetric($lines, 'iatechs_observability_threshold_subscriptions_past_due_max', 'Configured past_due subscriptions alert threshold.', 'gauge', [
            ['value' => (int) config('observability.subscriptions_past_due_alert', 5)],
        ]);
        $this->appendMetric($lines, 'iatechs_observability_threshold_churn_30d_max_percent', 'Configured maximum churn over 30 days in percent.', 'gauge', [
            ['value' => (float) config('observability.subscriptions_churn_30d_max', 5)],
        ]);

        return implode("\n", $lines)."\n";
    }

    /**
     * @param array<int, string> $lines
     * @param array<int, array{labels?: array<string, string>, value: int|float|string}> $samples
     */
    private function appendMetric(
        array &$lines,
        string $name,
        string $help,
        string $type,
        array $samples
    ): void {
        $lines[] = '# HELP '.$name.' '.$help;
        $lines[] = '# TYPE '.$name.' '.$type;

        foreach ($samples as $sample) {
            $labels = (array) ($sample['labels'] ?? []);
            $value = $this->normalizeNumericValue($sample['value']);
            $lines[] = $name.$this->formatLabels($labels).' '.$value;
        }
    }

    /**
     * @param array<string, string> $labels
     */
    private function formatLabels(array $labels): string
    {
        if ($labels === []) {
            return '';
        }

        ksort($labels);
        $formatted = [];
        foreach ($labels as $key => $value) {
            $formatted[] = $key.'="'.$this->escapeLabelValue($value).'"';
        }

        return '{'.implode(',', $formatted).'}';
    }

    private function escapeLabelValue(string $value): string
    {
        return str_replace(
            ['\\', '"', "\n", "\r"],
            ['\\\\', '\\"', '\\n', ''],
            $value
        );
    }

    private function normalizeNumericValue(int|float|string $value): string
    {
        if (is_string($value) && is_numeric($value)) {
            $value = (float) $value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            if (is_nan($value) || is_infinite($value)) {
                return '0';
            }

            $formatted = number_format($value, 6, '.', '');
            $formatted = rtrim(rtrim($formatted, '0'), '.');

            return $formatted === '' ? '0' : $formatted;
        }

        return '0';
    }

    private function severityToValue(string $severity): int
    {
        return match (strtoupper($severity)) {
            'HIGH' => 2,
            'MEDIUM' => 1,
            default => 0,
        };
    }

    private function toUnixTimestamp(mixed $value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        try {
            return Carbon::parse((string) $value)->timestamp;
        } catch (Throwable) {
            return 0;
        }
    }

    private function queueSize(): int
    {
        try {
            $queue = (string) config('queue.connections.redis.queue', 'default');

            return (int) Queue::size($queue);
        } catch (Throwable) {
            return 0;
        }
    }

    private function failedJobsCount(): int
    {
        try {
            return (int) DB::table('failed_jobs')->count();
        } catch (Throwable) {
            return 0;
        }
    }
}

