<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Domains\Plans\Models\Plan;
use App\Domains\Users\Models\User;
use App\Domains\Companies\Models\Company;
use App\Domains\Subscriptions\Models\Subscription;

class RegisterController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('portal.home');
        }

        return view('auth.register-start');
    }

    public function plans(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('portal.home');
        }

        $validated = $request->validate([
            'account_type' => ['nullable', Rule::in(['company', 'technician'])],
        ]);

        return view('auth.register', [
            'accountType' => $validated['account_type'] ?? 'company',
            'plans' => Plan::query()
                ->where('status', 'active')
                ->orderBy('monthly_price')
                ->get([
                    'id',
                    'name',
                    'description',
                    'monthly_price',
                    'yearly_price',
                    'trial_days',
                    'has_ai',
                    'has_inventory',
                    'has_reports',
                    'max_users',
                    'max_branches',
                    'max_tickets',
                ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_type' => ['required', Rule::in(['company', 'technician'])],
            'company_name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'company_email' => ['nullable', 'email', 'max:255', 'unique:companies,email'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'owner_phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'plan_id' => ['required', Rule::exists('plans', 'id')->where('status', 'active')],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
        ]);

        $company = DB::transaction(function () use ($validated): Company {
            $slug = $this->generateUniqueSlug($validated['company_name']);

            $company = Company::query()->create([
                'uuid' => (string) Str::uuid(),
                'name' => $validated['company_name'],
                'slug' => $slug,
                'email' => $validated['company_email'] ?? null,
                'status' => Company::STATUS_ACTIVE,
            ]);

            $owner = User::query()->create([
                'uuid' => (string) Str::uuid(),
                'company_id' => $company->id,
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'phone' => $validated['owner_phone'] ?? null,
                'password' => $validated['password'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $roleName = $validated['account_type'] === 'technician'
                ? 'technician'
                : 'owner';

            $primaryRole = Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $owner->assignRole($primaryRole);

            $plan = Plan::query()->findOrFail((int) $validated['plan_id']);

            if ($plan !== null) {
                $trialDays = max(0, (int) $plan->trial_days);
                $startsAt = today();
                $endsAt = $validated['billing_cycle'] === 'yearly'
                    ? $startsAt->copy()->addYear()
                    : $startsAt->copy()->addMonth();

                Subscription::query()->create([
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $company->id,
                    'plan_id' => $plan->id,
                    'billing_cycle' => $validated['billing_cycle'],
                    'amount' => $validated['billing_cycle'] === 'yearly'
                        ? $plan->yearly_price
                        : $plan->monthly_price,
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'trial_ends_at' => $trialDays > 0 ? now()->addDays($trialDays) : null,
                    'status' => 'active',
                ]);
            }

            return $company;
        });

        $user = User::query()
            ->where('company_id', $company->id)
            ->where('email', $validated['owner_email'])
            ->firstOrFail();

        Auth::login($user);
        $request->session()->regenerate();

        $isTechnician = $validated['account_type'] === 'technician';

        return redirect()
            ->route($isTechnician ? 'portal.technician.dashboard' : 'portal.company.dashboard')
            ->with(
                'ok',
                $isTechnician
                    ? 'Cuenta de tecnico creada correctamente.'
                    : 'Empresa creada correctamente. Bienvenido al portal.'
            );
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $root = $base !== '' ? $base : 'company';
        $slug = $root;
        $counter = 1;

        while (Company::query()->where('slug', $slug)->exists()) {
            $counter++;
            $slug = "{$root}-{$counter}";
        }

        return $slug;
    }
}
