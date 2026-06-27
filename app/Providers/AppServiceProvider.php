<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

use App\Tenant\Contracts\TenantResolverInterface;

use App\Tenant\Resolvers\TenantResolver;
use App\Tenant\Managers\TenantManager;
use SocialiteProviders\Microsoft\Provider as MicrosoftProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Tenant Resolver
        |--------------------------------------------------------------------------
        */

        $this->app->singleton(
            TenantResolverInterface::class,
            TenantResolver::class
        );

        /*
        |--------------------------------------------------------------------------
        | Tenant Manager
        |--------------------------------------------------------------------------
        */

        $this->app->singleton(
            TenantManager::class,
            fn () => new TenantManager()
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(SocialiteWasCalled::class, static function (SocialiteWasCalled $event): void {
            $event->extendSocialite('microsoft', MicrosoftProvider::class);
        });

        RateLimiter::for('login', function (Request $request): Limit {
            $email = (string) $request->input('email', '');

            return Limit::perMinute(5)->by(
                mb_strtolower($email).'|'.$request->ip()
            );
        });

        RateLimiter::for('register', function (Request $request): Limit {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('password-update', function (Request $request): Limit {
            $user = $request->user();
            $userId = $user ? (string) $user->id : 'guest';

            return Limit::perMinute(5)->by($userId.'|'.$request->ip());
        });

        RateLimiter::for('password-email', function (Request $request): Limit {
            $email = (string) $request->input('email', '');

            return Limit::perMinute(5)->by(
                mb_strtolower($email).'|'.$request->ip()
            );
        });

        RateLimiter::for('password-reset', function (Request $request): Limit {
            $email = (string) $request->input('email', '');

            return Limit::perMinute(8)->by(
                mb_strtolower($email).'|'.$request->ip()
            );
        });

        RateLimiter::for('api-typed', function (Request $request): array {
            $user = $request->user();
            $userId = $user ? (string) $user->id : 'guest';
            $route = (string) ($request->route()?->uri() ?? 'api');
            $bucket = $userId.'|'.$request->ip().'|'.$route;
            $path = $request->path();

            if (str_contains($path, 'ai') || str_contains($path, 'assistant')) {
                return [Limit::perMinute(30)->by('ai|'.$bucket)];
            }

            if ($request->isMethod('get') || $request->isMethod('head')) {
                return [Limit::perMinute(180)->by('read|'.$bucket)];
            }

            return [Limit::perMinute(90)->by('write|'.$bucket)];
        });
    }
}
