<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Tenant\Middleware\TenantMiddleware;
use App\Http\Middleware\EnsurePortalAccess;
use App\Http\Middleware\AttachRequestContext;
use App\Http\Middleware\RecordRequestMetrics;
use App\Http\Middleware\EnsureActiveSubscription;
use App\Http\Middleware\EnsurePlanAllowsPortalModule;
use App\Http\Middleware\EnsurePortalModuleAccess;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(
    basePath: dirname(__DIR__)
)

->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up'
)

->withMiddleware(function (
    Middleware $middleware
) {
    $middleware->statefulApi();

    $middleware->append([
        AttachRequestContext::class,
        RecordRequestMetrics::class,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Middleware Alias
    |--------------------------------------------------------------------------
    */

    $middleware->alias([

        'tenant' => TenantMiddleware::class,
        'portal.access' => EnsurePortalAccess::class,
        'portal.module' => EnsurePortalModuleAccess::class,
        'subscription.active' => EnsureActiveSubscription::class,
        'plan.module' => EnsurePlanAllowsPortalModule::class,
        'permission' => PermissionMiddleware::class,
        'role' => RoleMiddleware::class,
        'role_or_permission' => RoleOrPermissionMiddleware::class,

    ]);
})

->withExceptions(function (
    Exceptions $exceptions
) {
    //
})

->create();
