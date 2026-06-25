<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\Users\Models\User;
use App\Support\PlanAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanAllowsPortalModule
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        $portal = (string) ($request->route('portal') ?? 'company');
        $module = (string) $request->route('module');

        if ($module !== '' && !PlanAccess::hasModuleAccessByPlan($user, $portal, $module)) {
            abort(403, 'Tu plan actual no incluye este modulo.');
        }

        return $next($request);
    }
}

