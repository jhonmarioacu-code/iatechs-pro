<?php

declare(strict_types=1);

return [
    'payment_success_rate_min' => (float) env('OBS_PAYMENT_SUCCESS_RATE_MIN', 97),
    'payment_failed_24h_alert' => (int) env('OBS_PAYMENT_FAILED_24H_ALERT', 5),
    'pending_online_stale_minutes' => (int) env('OBS_PENDING_ONLINE_STALE_MINUTES', 30),
    'pending_online_alert' => (int) env('OBS_PENDING_ONLINE_ALERT', 10),
    'subscriptions_past_due_alert' => (int) env('OBS_SUBS_PAST_DUE_ALERT', 5),
    'subscriptions_churn_30d_max' => (float) env('OBS_SUBS_CHURN_30D_MAX', 5),

    'exporter' => [
        'enabled' => (bool) env('OBS_EXPORTER_ENABLED', false),
        'token' => (string) env('OBS_EXPORTER_TOKEN', ''),
        'allowed_ips' => array_values(array_filter(array_map(
            static fn (string $item): string => trim($item),
            explode(',', (string) env('OBS_EXPORTER_ALLOWED_IPS', '127.0.0.1,::1'))
        ))),
    ],

    'alerts' => [
        'enabled' => (bool) env('OBS_ALERTS_ENABLED', true),
        'email_enabled' => (bool) env('OBS_ALERTS_EMAIL_ENABLED', true),
        'email_recipients' => array_values(array_filter(array_map(
            static fn (string $item): string => mb_strtolower(trim($item)),
            explode(',', (string) env('OBS_ALERTS_EMAIL_RECIPIENTS', ''))
        ))),
        'slack_enabled' => (bool) env('OBS_ALERTS_SLACK_ENABLED', true),
        'slack_webhook_url' => (string) env(
            'OBS_ALERTS_SLACK_WEBHOOK_URL',
            (string) env('LOG_SLACK_WEBHOOK_URL', '')
        ),
        'cooldown_minutes' => (int) env('OBS_ALERTS_COOLDOWN_MINUTES', 30),
        'check_interval_minutes' => (int) env('OBS_ALERTS_CHECK_INTERVAL_MINUTES', 5),
    ],
];
