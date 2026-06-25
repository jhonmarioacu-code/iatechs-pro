<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HealthController extends Controller
{
    public function root(): View
    {
        return view('public.home');
    }

    public function health(): JsonResponse
    {
        return $this->buildHealthResponse('IAtechs Pro');
    }

    public function api(): JsonResponse
    {
        return $this->buildHealthResponse('IAtechs Pro API', 'v1');
    }

    private function buildHealthResponse(
        string $service,
        ?string $version = null
    ): JsonResponse {
        $checks = [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'queue' => $this->checkQueue(),
            'storage' => $this->checkStorage(),
        ];

        $allOk = collect($checks)->every(fn (array $check): bool => $check['ok'] === true);

        $payload = [
            'success' => $allOk,
            'service' => $service,
            'status' => $allOk ? 'ok' : 'degraded',
            'checks' => $checks,
            'metrics' => [
                'requests_total' => (int) Cache::get('metrics:http:requests_total', 0),
                'last_duration_ms' => (int) Cache::get('metrics:http:last_duration_ms', 0),
                'last_seen_at' => Cache::get('metrics:http:last_seen_at'),
            ],
        ];

        if ($version !== null) {
            $payload['version'] = $version;
        }

        return response()->json(
            $payload,
            $allOk ? 200 : 503
        );
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return ['ok' => true];
        } catch (Throwable $exception) {
            return ['ok' => false, 'error' => $exception->getMessage()];
        }
    }

    private function checkRedis(): array
    {
        try {
            $pong = Redis::connection()->ping();

            return ['ok' => (string) $pong === '1' || strtoupper((string) $pong) === 'PONG'];
        } catch (Throwable $exception) {
            return ['ok' => false, 'error' => $exception->getMessage()];
        }
    }

    private function checkQueue(): array
    {
        try {
            $defaultQueue = (string) config('queue.connections.redis.queue', 'default');
            $size = Queue::size($defaultQueue);

            return ['ok' => true, 'pending' => $size];
        } catch (Throwable $exception) {
            return ['ok' => false, 'error' => $exception->getMessage()];
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
                return ['ok' => false, 'error' => "Path not writable: {$path}"];
            }
        }

        return ['ok' => true];
    }
}
