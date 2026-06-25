<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Domains\Plans\Models\Plan;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Users\Models\User;
use App\Domains\Companies\Models\Company;
use App\Domains\Users\Services\UserService;
use App\Domains\Companies\Services\CompanyService;
use App\Domains\Subscriptions\Services\SubscriptionService;

class OperationsController extends Controller
{
    public function __construct(
        private CompanyService $companyService,
        private UserService $userService,
        private SubscriptionService $subscriptionService
    ) {}

    public function index(): View
    {
        $this->authorizeAdmin();

        return view('admin.operations', [
            'portalTitle' => 'Operaciones',
            'portalSubtitle' => 'Alta de empresa, usuarios y suscripciones.',
            'kpis' => [
                ['label' => 'Empresas', 'value' => (string) Company::query()->count(), 'trend' => 'global'],
                ['label' => 'Usuarios', 'value' => (string) User::query()->count(), 'trend' => 'global'],
                ['label' => 'Planes activos', 'value' => (string) Plan::query()->where('status', 'active')->count(), 'trend' => 'catalogo'],
                ['label' => 'Accion', 'value' => 'Alta', 'trend' => 'manual'],
            ],
            'companies' => Company::query()->orderBy('name')->get([
                'id',
                'name',
                'legal_name',
                'tax_id',
                'email',
                'phone',
                'website',
                'address',
                'city',
                'country',
                'status',
            ]),
            'plans' => Plan::query()->where('status', 'active')->orderBy('name')->get(['id', 'name', 'monthly_price', 'yearly_price']),
            'roles' => Role::query()->where('guard_name', 'web')->orderBy('name')->pluck('name'),
            'permissions' => Permission::query()->where('guard_name', 'web')->orderBy('name')->pluck('name'),
            'users' => User::query()
                ->with(['roles', 'permissions', 'company'])
                ->orderBy('name')
                ->get(['id', 'company_id', 'name', 'email', 'phone', 'is_active']),
            'technicians' => User::query()
                ->with(['roles', 'permissions', 'company'])
                ->whereHas('roles', static fn ($query) => $query->where('name', 'technician'))
                ->orderBy('name')
                ->get(['id', 'company_id', 'name', 'email']),
            'subscriptions' => Subscription::query()
                ->with(['company', 'plan'])
                ->latest()
                ->get(['id', 'company_id', 'plan_id', 'billing_cycle', 'amount', 'starts_at', 'ends_at', 'status']),
        ]);
    }

    public function storeCompany(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100', 'unique:companies,tax_id'],
            'email' => ['nullable', 'email', 'max:255', 'unique:companies,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'trial_ends_at' => ['nullable', 'date'],
        ]);

        $company = $this->companyService->create($data);

        return redirect()
            ->route('portal.admin.operations')
            ->with('ok', "Empresa creada: {$company->name}");
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? true);
        $data['email_verified_at'] = now();

        $user = $this->userService->create($data);

        return redirect()
            ->route('portal.admin.operations')
            ->with('ok', "Usuario creado: {$user->email}");
    }

    public function storeSubscription(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'plan_id' => ['required', Rule::exists('plans', 'id')->where('status', 'active')],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        if (!isset($data['amount'])) {
            $plan = Plan::query()->findOrFail((int) $data['plan_id']);
            $data['amount'] = $data['billing_cycle'] === 'yearly'
                ? $plan->yearly_price
                : $plan->monthly_price;
        }

        $this->subscriptionService->create($data);

        return redirect()
            ->route('portal.admin.operations')
            ->with('ok', 'Suscripcion creada correctamente.');
    }

    public function updateCompany(Request $request, Company $company): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('companies', 'name')->ignore($company->id)],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100', Rule::unique('companies', 'tax_id')->ignore($company->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($company->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'suspended', 'cancelled'])],
        ]);

        $this->companyService->update($company, $data);

        return redirect()
            ->route('portal.admin.operations')
            ->with('ok', "Empresa actualizada: {$company->name}");
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($user, &$data): void {
            $role = $data['role'];
            unset($data['role']);

            $data['is_active'] = (bool) ($data['is_active'] ?? false);

            if (!isset($data['password']) || $data['password'] === '') {
                unset($data['password']);
            }

            $updated = $this->userService->update($user, $data);
            $updated->syncRoles([$role]);
        });

        return redirect()
            ->route('portal.admin.operations')
            ->with('ok', "Usuario actualizado: {$user->email}");
    }

    public function updateSubscription(Request $request, Subscription $subscription): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'plan_id' => ['required', Rule::exists('plans', 'id')->where('status', 'active')],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'status' => ['required', Rule::in(['trial', 'active', 'past_due', 'cancelled', 'expired'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
        ]);

        if ($data['status'] === 'cancelled') {
            $data['cancelled_at'] = now();
        } else {
            $data['cancelled_at'] = null;
        }

        $this->subscriptionService->update($subscription, $data);

        return redirect()
            ->route('portal.admin.operations')
            ->with('ok', 'Suscripcion actualizada correctamente.');
    }

    public function syncTechnicianPermissions(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        abort_if(!$user->hasRole('technician'), 422, 'Solo se puede gestionar permisos sobre usuarios tecnicos.');

        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'web')],
        ]);

        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()
            ->route('portal.admin.operations')
            ->with('ok', "Permisos de tecnico actualizados: {$user->email}");
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->hasRole('super_admin'), 403);
    }
}
