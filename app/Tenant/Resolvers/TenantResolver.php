<?php

declare(strict_types=1);

namespace App\Tenant\Resolvers;

use Illuminate\Support\Facades\Auth;

use App\Domains\Companies\Models\Company;
use App\Tenant\Contracts\TenantResolverInterface;

class TenantResolver implements TenantResolverInterface
{
    public function resolve(): ?Company
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        if ($user->hasRole('super_admin')) {
            return null;
        }

        if (!isset($user->company_id)) {
            return null;
        }

        return Company::find(
            (int) $user->company_id
        );
    }
}
