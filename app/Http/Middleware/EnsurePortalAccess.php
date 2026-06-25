<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domains\Users\Models\User;

class EnsurePortalAccess
{
    public function handle(
        Request $request,
        Closure $next,
        ?string $expectedPortal = null
    ): Response {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $portal = $expectedPortal ?? (string) $request->route('portal');

        if ($portal === '' || !$this->canAccessPortal($user, $portal)) {
            abort(403);
        }

        return $next($request);
    }

    private function canAccessPortal(
        User $user,
        string $portal
    ): bool {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return match ($portal) {
            'admin' => false,
            'company' => $user->hasAnyRole([
                'owner',
                'administrator',
                'manager',
                'receptionist',
                'warehouse',
                'accountant',
            ]),
            'technician' => $user->hasRole('technician'),
            'customer' => $user->hasRole('customer'),
            default => false,
        };
    }
}
