<?php

declare(strict_types=1);

namespace App\Tenant;

use Illuminate\Support\ServiceProvider;

use App\Tenant\Managers\TenantManager;
use App\Tenant\Resolvers\TenantResolver;

use App\Tenant\Contracts\TenantResolverInterface;

class TenantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            TenantManager::class
        );

        $this->app->bind(
            TenantResolverInterface::class,
            TenantResolver::class
        );
    }

    public function boot(): void
    {
        //
    }
}
