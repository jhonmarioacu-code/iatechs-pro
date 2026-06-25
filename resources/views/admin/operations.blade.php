@extends('layouts.admin')

@section('portal-content')
    @if (session('ok'))
        <div class="crud-feedback success">{{ session('ok') }}</div>
    @endif

    @if ($errors->any())
        <div class="crud-feedback error">{{ $errors->first() }}</div>
    @endif

    <div class="surface-grid">
        <section class="surface-card">
            <header class="surface-header">
                <h2>Crear Empresa</h2>
            </header>

            <form class="crud-form" method="POST" action="{{ route('portal.admin.operations.company.store') }}">
                @csrf
                <div class="crud-grid">
                    <label class="crud-field">
                        <span class="crud-label">Nombre</span>
                        <input class="crud-input" name="name" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Razon social</span>
                        <input class="crud-input" name="legal_name">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">NIT</span>
                        <input class="crud-input" name="tax_id">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Email</span>
                        <input class="crud-input" name="email" type="email">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Telefono</span>
                        <input class="crud-input" name="phone">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Ciudad</span>
                        <input class="crud-input" name="city">
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Crear empresa</button>
            </form>
        </section>

        <section class="surface-card">
            <header class="surface-header">
                <h2>Crear Usuario</h2>
            </header>

            <form class="crud-form" method="POST" action="{{ route('portal.admin.operations.user.store') }}">
                @csrf
                <div class="crud-grid">
                    <label class="crud-field">
                        <span class="crud-label">Empresa</span>
                        <select class="crud-input" name="company_id" required>
                            <option value="">Seleccionar</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Nombre</span>
                        <input class="crud-input" name="name" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Email</span>
                        <input class="crud-input" name="email" type="email" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Contrasena</span>
                        <input class="crud-input" name="password" type="password" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Rol</span>
                        <select class="crud-input" name="role" required>
                            <option value="">Seleccionar</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="crud-checkbox">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Activo
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Crear usuario</button>
            </form>
        </section>
    </div>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Crear Suscripcion</h2>
        </header>

        <form class="crud-form" method="POST" action="{{ route('portal.admin.operations.subscription.store') }}">
            @csrf
            <div class="crud-grid">
                <label class="crud-field">
                    <span class="crud-label">Empresa</span>
                    <select class="crud-input" name="company_id" required>
                        <option value="">Seleccionar</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="crud-field">
                    <span class="crud-label">Plan</span>
                    <select class="crud-input" name="plan_id" required>
                        <option value="">Seleccionar</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} (M: {{ $plan->monthly_price }} / Y: {{ $plan->yearly_price }})</option>
                        @endforeach
                    </select>
                </label>
                <label class="crud-field">
                    <span class="crud-label">Ciclo</span>
                    <select class="crud-input" name="billing_cycle" required>
                        <option value="monthly">Mensual</option>
                        <option value="yearly">Anual</option>
                    </select>
                </label>
                <label class="crud-field">
                    <span class="crud-label">Monto (opcional)</span>
                    <input class="crud-input" name="amount" type="number" step="0.01" min="0">
                </label>
                <label class="crud-field">
                    <span class="crud-label">Inicio</span>
                    <input class="crud-input" name="starts_at" type="date">
                </label>
                <label class="crud-field">
                    <span class="crud-label">Fin</span>
                    <input class="crud-input" name="ends_at" type="date">
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Crear suscripcion</button>
        </form>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Editar Empresas</h2>
        </header>

        @foreach ($companies as $company)
            <form class="crud-form" method="POST" action="{{ route('portal.admin.operations.company.update', $company) }}">
                @csrf
                @method('PUT')
                <div class="crud-grid">
                    <input type="hidden" name="id" value="{{ $company->id }}">
                    <label class="crud-field">
                        <span class="crud-label">Nombre</span>
                        <input class="crud-input" name="name" value="{{ $company->name }}" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Razon social</span>
                        <input class="crud-input" name="legal_name" value="{{ $company->legal_name }}">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Email</span>
                        <input class="crud-input" name="email" type="email" value="{{ $company->email }}">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Telefono</span>
                        <input class="crud-input" name="phone" value="{{ $company->phone }}">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Ciudad</span>
                        <input class="crud-input" name="city" value="{{ $company->city }}">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Pais</span>
                        <input class="crud-input" name="country" value="{{ $company->country }}">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Estado</span>
                        <select class="crud-input" name="status" required>
                            <option value="active" @selected($company->status === 'active')>Activa</option>
                            <option value="suspended" @selected($company->status === 'suspended')>Suspendida</option>
                            <option value="cancelled" @selected($company->status === 'cancelled')>Cancelada</option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar empresa</button>
            </form>
            <hr>
        @endforeach
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Editar Usuarios</h2>
        </header>

        @foreach ($users as $user)
            <form class="crud-form" method="POST" action="{{ route('portal.admin.operations.user.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="crud-grid">
                    <label class="crud-field">
                        <span class="crud-label">Empresa</span>
                        <select class="crud-input" name="company_id" required>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected($company->id === $user->company_id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Nombre</span>
                        <input class="crud-input" name="name" value="{{ $user->name }}" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Email</span>
                        <input class="crud-input" name="email" type="email" value="{{ $user->email }}" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Telefono</span>
                        <input class="crud-input" name="phone" value="{{ $user->phone }}">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Nueva contrasena (opcional)</span>
                        <input class="crud-input" name="password" type="password">
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Rol</span>
                        <select class="crud-input" name="role" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected($user->roles->pluck('name')->contains($role))>{{ $role }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="crud-checkbox">
                        <input type="checkbox" name="is_active" value="1" @checked($user->is_active)>
                        Activo
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar usuario</button>
            </form>
            <hr>
        @endforeach
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Editar Suscripciones</h2>
        </header>

        @foreach ($subscriptions as $subscription)
            <form class="crud-form" method="POST" action="{{ route('portal.admin.operations.subscription.update', $subscription) }}">
                @csrf
                @method('PUT')
                <div class="crud-grid">
                    <label class="crud-field">
                        <span class="crud-label">Empresa</span>
                        <select class="crud-input" name="company_id" required>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected($company->id === $subscription->company_id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Plan</span>
                        <select class="crud-input" name="plan_id" required>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" @selected($plan->id === $subscription->plan_id)>{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Ciclo</span>
                        <select class="crud-input" name="billing_cycle" required>
                            <option value="monthly" @selected($subscription->billing_cycle === 'monthly')>Mensual</option>
                            <option value="yearly" @selected($subscription->billing_cycle === 'yearly')>Anual</option>
                        </select>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Estado</span>
                        <select class="crud-input" name="status" required>
                            <option value="trial" @selected($subscription->status === 'trial')>Trial</option>
                            <option value="active" @selected($subscription->status === 'active')>Active</option>
                            <option value="past_due" @selected($subscription->status === 'past_due')>Past Due</option>
                            <option value="cancelled" @selected($subscription->status === 'cancelled')>Cancelled</option>
                            <option value="expired" @selected($subscription->status === 'expired')>Expired</option>
                        </select>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Monto</span>
                        <input class="crud-input" name="amount" type="number" step="0.01" min="0" value="{{ $subscription->amount }}" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Inicio</span>
                        <input class="crud-input" name="starts_at" type="date" value="{{ optional($subscription->starts_at)->toDateString() }}" required>
                    </label>
                    <label class="crud-field">
                        <span class="crud-label">Fin</span>
                        <input class="crud-input" name="ends_at" type="date" value="{{ optional($subscription->ends_at)->toDateString() }}" required>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar suscripcion</button>
            </form>
            <hr>
        @endforeach
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Permisos de Tecnicos</h2>
        </header>

        @foreach ($technicians as $technician)
            <form class="crud-form" method="POST" action="{{ route('portal.admin.operations.user.permissions.sync', $technician) }}">
                @csrf
                <p class="module-copy">
                    <strong>{{ $technician->name }}</strong> ({{ $technician->email }}) | Empresa: {{ $technician->company?->name ?? 'N/A' }}
                </p>
                <div class="crud-grid">
                    @foreach ($permissions as $permission)
                        <label class="crud-checkbox">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission }}"
                                @checked($technician->permissions->pluck('name')->contains($permission))
                            >
                            {{ $permission }}
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary">Guardar permisos tecnico</button>
            </form>
            <hr>
        @endforeach
    </section>
@endsection
