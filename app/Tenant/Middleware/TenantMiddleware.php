<?php

declare(strict_types=1);

namespace App\Tenant\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Tenant\Managers\TenantManager;
use App\Tenant\Contracts\TenantResolverInterface;

class TenantMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $company = app(
            TenantResolverInterface::class
        )->resolve();

        if ($company) {

            app(
                TenantManager::class
            )->setTenant(
                $company
            );
        }

        $response = $next($request);

        app(
            TenantManager::class
        )->forgetTenant();

        return $response;
    }
}