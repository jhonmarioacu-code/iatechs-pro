<?php

declare(strict_types=1);

namespace App\Support;

use App\Domains\Users\Models\User;

final class PortalRedirector
{
    public static function routeForUser(User $user): string
    {
        if ($user->hasRole('super_admin')) {
            return route('portal.admin.dashboard');
        }

        if ($user->hasRole('customer')) {
            return route('portal.customer.dashboard');
        }

        if ($user->hasRole('technician')) {
            return route('portal.technician.dashboard');
        }

        if ($user->hasAnyRole([
            'owner',
            'administrator',
            'manager',
            'receptionist',
            'warehouse',
            'accountant',
        ])) {
            return route('portal.company.dashboard');
        }

        return route('portal.company.dashboard');
    }
}
