<?php

declare(strict_types=1);

namespace App\Tenant\Contracts;

use App\Domains\Companies\Models\Company;

interface TenantResolverInterface
{
    /**
     * Resolve current tenant company.
     */
    public function resolve(): ?Company;
}