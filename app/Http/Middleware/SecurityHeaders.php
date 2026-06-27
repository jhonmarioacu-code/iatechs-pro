<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $this->setHeader($response, 'X-Frame-Options', (string) config('security.headers.x_frame_options'));
        $this->setHeader($response, 'X-Content-Type-Options', (string) config('security.headers.x_content_type_options'));
        $this->setHeader($response, 'Referrer-Policy', (string) config('security.headers.referrer_policy'));
        $this->setHeader($response, 'Permissions-Policy', (string) config('security.headers.permissions_policy'));
        $this->setHeader($response, 'X-XSS-Protection', (string) config('security.headers.x_xss_protection'));
        $this->setHeader($response, 'Cross-Origin-Opener-Policy', (string) config('security.headers.cross_origin_opener_policy'));
        $this->setHeader($response, 'Cross-Origin-Resource-Policy', (string) config('security.headers.cross_origin_resource_policy'));
        $this->setHeader($response, 'Content-Security-Policy', (string) config('security.headers.content_security_policy'));

        if ($this->shouldSetHsts($request)) {
            $this->setHeader($response, 'Strict-Transport-Security', (string) config('security.headers.strict_transport_security'));
        }

        return $response;
    }

    private function setHeader(Response $response, string $name, string $value): void
    {
        if ($value !== '' && !$response->headers->has($name)) {
            $response->headers->set($name, $value);
        }
    }

    private function shouldSetHsts(Request $request): bool
    {
        if (!(bool) config('security.hsts_enabled')) {
            return false;
        }

        if ($request->isSecure()) {
            return true;
        }

        $forwardedProto = strtolower((string) $request->headers->get('X-Forwarded-Proto', ''));

        return $forwardedProto === 'https';
    }
}
