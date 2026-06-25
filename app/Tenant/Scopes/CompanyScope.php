<?php

declare(strict_types=1);

namespace App\Tenant\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use App\Tenant\Managers\TenantManager;

class CompanyScope implements Scope
{
    public function apply(
        Builder $builder,
        Model $model
    ): void {

        /**
         * Permite comandos artisan,
         * seeders y migraciones.
         */
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        $tenant = app(
            TenantManager::class
        );

        /**
         * No existe tenant cargado.
         */
        if (!$tenant->hasTenant()) {
            return;
        }

        $builder->where(
            $model->getTable() . '.company_id',
            $tenant->companyId()
        );
    }
}
