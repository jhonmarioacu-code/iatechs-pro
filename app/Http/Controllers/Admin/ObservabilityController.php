<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Throwable;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use App\Support\Observability\RevenueObservabilityService;

class ObservabilityController extends Controller
{
    public function __construct(
        private readonly RevenueObservabilityService $revenueObservabilityService
    ) {}

    public function index(): View
    {
        $revenueObservability = $this->revenueObservabilityService->snapshot();

        return view('admin.observability', [
            'portalTitle' => 'Observability',
            'portalSubtitle' => 'Operacion diaria con estado tecnico consolidado.',
            'kpis' => $this->kpis($revenueObservability),
            'services' => $this->services(),
            'statusBuckets' => $this->statusBuckets(),
            'horizon' => $this->horizonStatus(),
            'queueDetails' => $this->queueDetails(),
            'logDetails' => $this->logDetails(),
            'revenueObservability' => $revenueObservability,
        ]);
    }

    private function kpis(array $revenueObservability): array
    {
        $paymentSuccessRate = (float) ($revenueObservability['payments']['success_rate_24h'] ?? 100.0);
        $pastDueSubscriptions = (int) ($revenueObservability['subscriptions']['past_due'] ?? 0);
        $mrr = (float) ($revenueObservability['revenue']['mrr'] ?? 0);
        $overall = (string) ($revenueObservability['overall_status'] ?? 'OK');

        return [
            [
                'label' => 'Payment Success 24h',
                'value' => $paymentSuccessRate.'%',
                'trend' => 'SLO',
            ],
            [
                'label' => 'Past Due Subs',
                'value' => (string) $pastDueSubscriptions,
                'trend' => 'SLA',
            ],
            [
                'label' => 'MRR',
                'value' => '$ '.number_format($mrr, 2, '.', ','),
                'trend' => 'Run-rate',
            ],
            [
                'label' => 'Obs Status',
                'value' => $overall,
                'trend' => 'Global',
            ],
        ];
    }

    private function services(): array
    {
        return [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'queue' => $this->checkQueue(),
            'storage' => $this->checkStorage(),
        ];
    }

    private function statusBuckets(): array
    {
        $codes = [200, 401, 403, 404, 429, 500];
        $status = [];

        foreach ($codes as $code) {
            $status[] = [
                'code' => $code,
                'count' => (int) Cache::get("metrics:http:status:{$code}", 0),
            ];
        }

        return $status;
    }

    private function horizonStatus(): array
    {
        try {
            Artisan::call('horizon:status');
            $raw = trim(Artisan::output());
            $isRunning = str_contains(strtolower($raw), 'running');

            return [
                'ok' => $isRunning,
                'message' => $raw === '' ? 'Sin salida de comando.' : $raw,
            ];
        } catch (Throwable $exception) {
            Log::warning('horizon.status.failed', [
                'error' => $exception->getMessage(),
            ]);

            return [
                'ok' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }

    private function queueDetails(): array
    {
        $default = (string) config('queue.default');
        $redisQueue = (string) config('queue.connections.redis.queue', 'default');

        return [
            'default_connection' => $default,
            'redis_queue' => $redisQueue,
            'pending_jobs' => $this->safeQueueSize(),
            'failed_jobs' => $this->safeFailedJobsCount(),
        ];
    }

    private function logDetails(): array
    {
        $logPath = storage_path('logs/laravel.log');
        $exists = is_file($logPath);

        return [
            'path' => $logPath,
            'exists' => $exists,
            'size_kb' => $exists ? (int) ceil((int) filesize($logPath) / 1024) : 0,
            'last_modified_at' => $exists ? date('c', (int) filemtime($logPath)) : null,
            'last_seen_at' => Cache::get('metrics:http:last_seen_at'),
        ];
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return ['ok' => true, 'message' => 'OK'];
        } catch (Throwable $exception) {
            return ['ok' => false, 'message' => $exception->getMessage()];
        }
    }

    private function checkRedis(): array
    {
        try {
            $pong = Redis::connection()->ping();
            $ok = (string) $pong === '1' || strtoupper((string) $pong) === 'PONG';

            return ['ok' => $ok, 'message' => $ok ? 'PONG' : 'Respuesta inesperada'];
        } catch (Throwable $exception) {
            return ['ok' => false, 'message' => $exception->getMessage()];
        }
    }

    private function checkQueue(): array
    {
        try {
            return [
                'ok' => true,
                'message' => 'pending='.$this->safeQueueSize(),
            ];
        } catch (Throwable $exception) {
            return ['ok' => false, 'message' => $exception->getMessage()];
        }
    }

    private function checkStorage(): array
    {
        $paths = [
            storage_path('logs'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
        ];

        foreach ($paths as $path) {
            if (!is_dir($path) || !is_writable($path)) {
                return [
                    'ok' => false,
                    'message' => "No writable: {$path}",
                ];
            }
        }

        return ['ok' => true, 'message' => 'OK'];
    }

    private function safeQueueSize(): int
    {
        try {
            $queue = (string) config('queue.connections.redis.queue', 'default');

            return Queue::size($queue);
        } catch (Throwable $exception) {
            return 0;
        }
    }

    private function safeFailedJobsCount(): int
    {
        try {
            return (int) DB::table('failed_jobs')->count();
        } catch (Throwable $exception) {
            return 0;
        }
    }
}
