<?php

declare(strict_types=1);

namespace App\Tenant\Traits;

use RuntimeException;

use Illuminate\Database\Eloquent\Model;

use App\Tenant\Scopes\CompanyScope;
use App\Tenant\Managers\TenantManager;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(
            new CompanyScope()
        );

        static::creating(
            function (Model $model) {

                if ($model->getAttribute('company_id') !== null) {
                    return;
                }

                $tenant = app(
                    TenantManager::class
                );

                if (
                    !$tenant->hasTenant()
                ) {

                    throw new RuntimeException(
                        'Tenant not resolved.'
                    );
                }

                $model->setAttribute(
                    'company_id',
                    $tenant->companyId()
                );
            }
        );
    }

    public function company()
    {
        return $this->belongsTo(
            \App\Domains\Companies\Models\Company::class
        );
    }
}
