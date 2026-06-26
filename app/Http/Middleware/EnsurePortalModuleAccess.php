<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\Users\Models\User;
use App\Support\PortalMatrix;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalModuleAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $portal = (string) ($request->route('portal') ?? '');
        $module = (string) ($request->route('module') ?? '');

        if ($portal !== '' && $module !== '' && !PortalMatrix::canAccessModule($user, $portal, $module)) {
            abort(403);
        }

        return $next($request);
    }
}

