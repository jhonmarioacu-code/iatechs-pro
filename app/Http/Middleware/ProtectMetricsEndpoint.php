<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

class ProtectMetricsEndpoint
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! (bool) config('observability.exporter.enabled', false)) {
            abort(404);
        }

        $this->validateToken($request);
        $this->validateIpAllowlist($request);

        return $next($request);
    }

    private function validateToken(Request $request): void
    {
        $configuredToken = trim((string) config('observability.exporter.token', ''));
        if ($configuredToken === '') {
            Log::error('observability.exporter.misconfigured', [
                'reason' => 'missing_token',
            ]);

            abort(503, 'Metrics exporter is misconfigured.');
        }

        $providedToken = trim((string) $request->header('X-Metrics-Token'));
        if ($providedToken === '') {
            $authorization = trim((string) $request->header('Authorization'));
            if (preg_match('/^Bearer\s+(.+)$/i', $authorization, $matches) === 1) {
                $providedToken = trim((string) $matches[1]);
            }
        }

        if ($providedToken === '' || ! hash_equals($configuredToken, $providedToken)) {
            abort(401, 'Unauthorized.');
        }
    }

    private function validateIpAllowlist(Request $request): void
    {
        $allowlist = array_values(array_filter(array_map(
            static fn (mixed $item): string => trim((string) $item),
            (array) config('observability.exporter.allowed_ips', [])
        )));

        if ($allowlist === []) {
            return;
        }

        $clientIp = (string) $request->ip();
        foreach ($allowlist as $allowedIp) {
            if ($allowedIp === '*' || IpUtils::checkIp($clientIp, $allowedIp)) {
                return;
            }
        }

        abort(403, 'Forbidden.');
    }
}
