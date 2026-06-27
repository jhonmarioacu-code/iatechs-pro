<?php

declare(strict_types=1);

namespace App\Support\Observability;

use Throwable;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Domains\Notifications\Services\NotificationService;

class ObservabilityAlertDispatcher
{
    public function __construct(
        private readonly RevenueObservabilityService $revenueObservabilityService,
        private readonly NotificationService $notificationService
    ) {}

    public function dispatch(bool $force = false): array
    {
        if (! (bool) config('observability.alerts.enabled', true)) {
            return [
                'status' => 'disabled',
                'degraded_count' => 0,
                'dispatched_count' => 0,
                'suppressed_count' => 0,
                'results' => [],
                'generated_at' => now()->toIso8601String(),
            ];
        }

        $snapshot = $this->revenueObservabilityService->snapshot();
        $degradedAlerts = collect((array) ($snapshot['alerts'] ?? []))
            ->filter(fn (array $alert): bool => strtoupper((string) ($alert['severity'] ?? 'OK')) !== 'OK')
            ->values();

        if ($degradedAlerts->isEmpty()) {
            return [
                'status' => 'healthy',
                'degraded_count' => 0,
                'dispatched_count' => 0,
                'suppressed_count' => 0,
                'results' => [],
                'generated_at' => (string) ($snapshot['generated_at'] ?? now()->toIso8601String()),
            ];
        }

        $dispatchedCount = 0;
        $suppressedCount = 0;
        $results = [];
        foreach ($degradedAlerts as $alert) {
            $cooldownKey = $this->cooldownCacheKey($alert);
            if (! $force && Cache::has($cooldownKey)) {
                $suppressedCount++;
                $results[] = [
                    'alert' => (string) ($alert['name'] ?? 'Unknown'),
                    'severity' => strtoupper((string) ($alert['severity'] ?? 'MEDIUM')),
                    'dispatched' => false,
                    'reason' => 'cooldown_active',
                ];
                continue;
            }

            $emailResult = $this->dispatchEmailAlert($alert, $snapshot);
            $slackResult = $this->dispatchSlackAlert($alert, $snapshot);
            $dispatched = $emailResult['sent'] > 0 || $slackResult['sent'] === true;

            if ($dispatched) {
                $dispatchedCount++;
                Cache::put($cooldownKey, now()->toIso8601String(), now()->addMinutes($this->cooldownMinutes()));
            } else {
                $suppressedCount++;
            }

            $results[] = [
                'alert' => (string) ($alert['name'] ?? 'Unknown'),
                'severity' => strtoupper((string) ($alert['severity'] ?? 'MEDIUM')),
                'dispatched' => $dispatched,
                'email' => $emailResult,
                'slack' => $slackResult,
            ];
        }

        return [
            'status' => 'degraded',
            'degraded_count' => $degradedAlerts->count(),
            'dispatched_count' => $dispatchedCount,
            'suppressed_count' => $suppressedCount,
            'results' => $results,
            'generated_at' => (string) ($snapshot['generated_at'] ?? now()->toIso8601String()),
        ];
    }

    /**
     * @param array{name?: string, severity?: string, value?: string, target?: string} $alert
     * @param array<string, mixed> $snapshot
     * @return array{sent: int, targets: array<int, string>, unresolved: array<int, string>}
     */
    private function dispatchEmailAlert(array $alert, array $snapshot): array
    {
        if (! (bool) config('observability.alerts.email_enabled', true)) {
            return [
                'sent' => 0,
                'targets' => [],
                'unresolved' => [],
            ];
        }

        $resolved = $this->resolveEmailTargets();
        /** @var Collection<int, User> $targets */
        $targets = $resolved['targets'];
        /** @var array<int, string> $unresolved */
        $unresolved = $resolved['unresolved'];

        if ($targets->isEmpty()) {
            if ($unresolved !== []) {
                Log::warning('observability.alert.email.unresolved_recipients', [
                    'recipients' => $unresolved,
                ]);
            }

            return [
                'sent' => 0,
                'targets' => [],
                'unresolved' => $unresolved,
            ];
        }

        $sent = 0;
        $targetEmails = [];
        foreach ($targets as $target) {
            try {
                $this->notificationService->create([
                    'company_id' => (int) $target->company_id,
                    'user_id' => (int) $target->id,
                    'title' => sprintf(
                        '[Observability][%s] %s',
                        strtoupper((string) ($alert['severity'] ?? 'MEDIUM')),
                        (string) ($alert['name'] ?? 'Alert')
                    ),
                    'message' => $this->buildHumanReadableMessage($alert, $snapshot),
                    'type' => 'OBSERVABILITY_ALERT',
                    'channel' => 'EMAIL',
                    'recipient' => (string) $target->email,
                    'subject' => sprintf(
                        '[IAtechs Pro] Alerta %s - %s',
                        strtoupper((string) ($alert['severity'] ?? 'MEDIUM')),
                        (string) ($alert['name'] ?? 'Alert')
                    ),
                    'data' => [
                        'observability_alert' => [
                            'name' => (string) ($alert['name'] ?? 'Alert'),
                            'severity' => strtoupper((string) ($alert['severity'] ?? 'MEDIUM')),
                            'value' => (string) ($alert['value'] ?? ''),
                            'target' => (string) ($alert['target'] ?? ''),
                            'generated_at' => (string) ($snapshot['generated_at'] ?? now()->toIso8601String()),
                            'overall_status' => (string) ($snapshot['overall_status'] ?? 'OK'),
                        ],
                    ],
                ]);

                $sent++;
                $targetEmails[] = mb_strtolower((string) $target->email);
            } catch (Throwable $exception) {
                Log::error('observability.alert.email.dispatch_failed', [
                    'alert' => (string) ($alert['name'] ?? 'Alert'),
                    'recipient' => (string) $target->email,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return [
            'sent' => $sent,
            'targets' => array_values(array_unique($targetEmails)),
            'unresolved' => $unresolved,
        ];
    }

    /**
     * @param array{name?: string, severity?: string, value?: string, target?: string} $alert
     * @param array<string, mixed> $snapshot
     * @return array{sent: bool, reason: string}
     */
    private function dispatchSlackAlert(array $alert, array $snapshot): array
    {
        if (! (bool) config('observability.alerts.slack_enabled', true)) {
            return [
                'sent' => false,
                'reason' => 'disabled',
            ];
        }

        $webhookUrl = trim((string) config('observability.alerts.slack_webhook_url', ''));
        if ($webhookUrl === '') {
            return [
                'sent' => false,
                'reason' => 'missing_webhook',
            ];
        }

        $payload = [
            'text' => sprintf(
                "[IAtechs Pro][Observability][%s] %s\nValor: %s | Objetivo: %s\nEstado global: %s | Generado: %s",
                strtoupper((string) ($alert['severity'] ?? 'MEDIUM')),
                (string) ($alert['name'] ?? 'Alert'),
                (string) ($alert['value'] ?? ''),
                (string) ($alert['target'] ?? ''),
                (string) ($snapshot['overall_status'] ?? 'UNKNOWN'),
                (string) ($snapshot['generated_at'] ?? now()->toIso8601String())
            ),
        ];

        try {
            $response = Http::timeout(8)->asJson()->post($webhookUrl, $payload);
            if (! $response->successful()) {
                Log::warning('observability.alert.slack.non_success', [
                    'status' => $response->status(),
                    'body' => Str::limit((string) $response->body(), 500),
                ]);

                return [
                    'sent' => false,
                    'reason' => 'http_'.$response->status(),
                ];
            }

            return [
                'sent' => true,
                'reason' => 'ok',
            ];
        } catch (Throwable $exception) {
            Log::error('observability.alert.slack.dispatch_failed', [
                'error' => $exception->getMessage(),
            ]);

            return [
                'sent' => false,
                'reason' => 'exception',
            ];
        }
    }

    /**
     * @param array{name?: string, severity?: string, value?: string, target?: string} $alert
     */
    private function cooldownCacheKey(array $alert): string
    {
        return 'observability:alerts:cooldown:'.sha1(
            strtoupper((string) ($alert['name'] ?? 'alert')).'|'.strtoupper((string) ($alert['severity'] ?? 'MEDIUM'))
        );
    }

    private function cooldownMinutes(): int
    {
        return max(1, min((int) config('observability.alerts.cooldown_minutes', 30), 1440));
    }

    /**
     * @param array{name?: string, severity?: string, value?: string, target?: string} $alert
     * @param array<string, mixed> $snapshot
     */
    private function buildHumanReadableMessage(array $alert, array $snapshot): string
    {
        $lines = [
            'Se detecto degradacion de observabilidad en el modulo de pagos/suscripciones.',
            'Alerta: '.(string) ($alert['name'] ?? 'Alert'),
            'Severidad: '.strtoupper((string) ($alert['severity'] ?? 'MEDIUM')),
            'Valor actual: '.(string) ($alert['value'] ?? ''),
            'Objetivo SLO/SLA: '.(string) ($alert['target'] ?? ''),
            'Estado global: '.(string) ($snapshot['overall_status'] ?? 'UNKNOWN'),
            'Generado: '.(string) ($snapshot['generated_at'] ?? now()->toIso8601String()),
            '',
            'Revisar dashboard: /admin/observability',
        ];

        return implode("\n", $lines);
    }

    /**
     * @return array{targets: Collection<int, User>, unresolved: array<int, string>}
     */
    private function resolveEmailTargets(): array
    {
        $superAdmins = User::query()
            ->select(['id', 'company_id', 'email', 'name'])
            ->active()
            ->whereNotNull('company_id')
            ->whereHas('roles', static function ($query): void {
                $query->where('name', 'super_admin');
            })
            ->get();

        $configuredRecipients = collect((array) config('observability.alerts.email_recipients', []))
            ->map(static fn (mixed $recipient): string => mb_strtolower(trim((string) $recipient)))
            ->filter(static fn (string $recipient): bool => $recipient !== '')
            ->unique()
            ->values();

        if ($configuredRecipients->isEmpty()) {
            return [
                'targets' => $superAdmins,
                'unresolved' => [],
            ];
        }

        $superAdminsByEmail = $superAdmins->keyBy(
            static fn (User $user): string => mb_strtolower((string) $user->email)
        );

        $targets = $configuredRecipients
            ->map(static fn (string $email): ?User => $superAdminsByEmail->get($email))
            ->filter(static fn (?User $user): bool => $user !== null)
            ->values();

        $unresolved = $configuredRecipients
            ->filter(static fn (string $email): bool => ! $superAdminsByEmail->has($email))
            ->values()
            ->all();

        return [
            'targets' => $targets,
            'unresolved' => $unresolved,
        ];
    }
}

