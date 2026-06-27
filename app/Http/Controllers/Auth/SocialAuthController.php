<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Domains\Users\Models\User;
use App\Support\PortalRedirector;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthController extends Controller
{
    private const PROVIDERS = [
        'google',
        'github',
        'microsoft',
    ];

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless($this->isSupportedProvider($provider), 404);

        if (!$this->isProviderConfigured($provider)) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'El proveedor OAuth no esta configurado.',
                ]);
        }

        return Socialite::driver($provider)
            ->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        abort_unless($this->isSupportedProvider($provider), 404);

        if (!$this->isProviderConfigured($provider)) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'El proveedor OAuth no esta configurado.',
                ]);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'No fue posible autenticar con el proveedor seleccionado.',
                ]);
        }

        $email = mb_strtolower(trim((string) $socialUser->getEmail()));

        if ($email === '') {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'El proveedor OAuth no retorno un correo valido.',
                ]);
        }

        /** @var User|null $user */
        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if ($user === null || !$user->is_active) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Tu cuenta no esta autorizada para acceso social.',
                ]);
        }

        $user->forceFill([
            'social_provider' => $provider,
            'social_provider_id' => (string) $socialUser->getId(),
            'last_login_at' => now(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(PortalRedirector::routeForUser($user));
    }

    private function isSupportedProvider(string $provider): bool
    {
        return in_array($provider, self::PROVIDERS, true);
    }

    private function isProviderConfigured(string $provider): bool
    {
        $config = (array) config("services.{$provider}");

        return ($config['client_id'] ?? '') !== ''
            && ($config['client_secret'] ?? '') !== ''
            && ($config['redirect'] ?? '') !== '';
    }
}
