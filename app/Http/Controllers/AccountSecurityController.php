<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Domains\Users\Models\User;

class AccountSecurityController extends Controller
{
    public function edit(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('account.security', [
            'portal' => $this->portalFor($user),
            'portalTitle' => 'Seguridad de cuenta',
            'portalSubtitle' => 'Actualiza tu contrasena de acceso.',
            'kpis' => [],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        if (!Hash::check($validated['current_password'], (string) $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contrasena actual es incorrecta.',
            ]);
        }

        $user->forceFill([
            'password' => $validated['password'],
        ])->save();

        return redirect()
            ->route('portal.account.security.edit')
            ->with('ok', 'Contrasena actualizada correctamente.');
    }

    private function portalFor(User $user): string
    {
        if ($user->hasRole('super_admin')) {
            return 'admin';
        }

        if ($user->hasRole('customer')) {
            return 'customer';
        }

        if ($user->hasRole('technician')) {
            return 'technician';
        }

        return 'company';
    }
}
