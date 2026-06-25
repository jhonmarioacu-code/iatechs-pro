<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RecordRequestMetrics
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $startedAt = microtime(true);

        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            $this->record(
                $request,
                500,
                (int) round((microtime(true) - $startedAt) * 1000)
            );

            throw $exception;
        }

        $this->record(
            $request,
            $response->getStatusCode(),
            (int) round((microtime(true) - $startedAt) * 1000)
        );

        return $response;
    }

    private function record(
        Request $request,
        int $status,
        int $durationMs
    ): void {
        $prefix = 'metrics:http';
        $route = (string) ($request->route()?->getName() ?? $request->path());

        $this->increment("{$prefix}:requests_total");
        $this->increment("{$prefix}:status:{$status}");
        $this->increment("{$prefix}:route:".md5($route));
        $this->set("{$prefix}:last_duration_ms", $durationMs);
        $this->set("{$prefix}:last_seen_at", now()->toIso8601String());
    }

    private function increment(string $key): void
    {
        if (!Cache::has($key)) {
            Cache::put($key, 0, now()->addDay());
        }

        Cache::increment($key);
    }

    private function set(string $key, mixed $value): void
    {
        Cache::put($key, $value, now()->addDay());
    }
}
