<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class HorizonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Horizon::auth(function ($request): bool {
            $user = $request->user();

            if (!$user) {
                return false;
            }

            return $user->hasRole('super_admin');
        });
    }
}
