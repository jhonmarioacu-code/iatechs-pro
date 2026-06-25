<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Support\PortalRedirector;

class AuthenticatedSessionController extends Controller
{
    public function create(Request $request): RedirectResponse|\Illuminate\View\View
    {
        if (Auth::check()) {
            /** @var \App\Domains\Users\Models\User $user */
            $user = Auth::user();

            return redirect()->to(PortalRedirector::routeForUser($user));
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $throttleKey = Str::lower((string) $request->input('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Demasiados intentos. Intenta nuevamente en {$seconds} segundos.",
            ]);
        }

        $remember = (bool) ($credentials['remember'] ?? false);

        if (!Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $remember)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Credenciales invalidas o usuario inactivo.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        /** @var \App\Domains\Users\Models\User $user */
        $user = $request->user();
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(PortalRedirector::routeForUser($user));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
