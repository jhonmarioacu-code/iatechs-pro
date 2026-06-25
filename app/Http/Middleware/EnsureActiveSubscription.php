<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\Users\Models\User;
use App\Support\PlanAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
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

        $company = $user->company;

        if (!$company || !$company->isActive()) {
            abort(403, 'Tu empresa no esta activa.');
        }

        if (!PlanAccess::hasValidSubscription($user)) {
            abort(403, 'Tu empresa no tiene una suscripcion activa.');
        }

        return $next($request);
    }
}

