@extends('layouts.base')

@section('title', 'IAtechs Pro | Planes y Suscripcion')

@section('body')
    <main class="public-hero onboarding-flow">
        <section class="public-hero-inner">
            <p class="public-kicker">IAtechs Pro</p>
            <h1>Planes y suscripcion</h1>
            <p class="public-summary">
                Perfil seleccionado:
                <strong>{{ $accountType === 'technician' ? 'Tecnico independiente' : 'Empresa' }}</strong>.
                Escoge el plan para activar funciones segun tu suscripcion.
            </p>

            <div class="public-actions">
                <a href="{{ route('register') }}" class="btn btn-secondary">Cambiar perfil</a>
                <a href="{{ route('login') }}" class="btn btn-secondary">Ya tengo cuenta</a>
            </div>
        </section>

        <section class="surface-card auth-card plans-card">
            <header class="surface-header">
                <h2>Selecciona tu plan</h2>
            </header>

            @if ($errors->any())
                <div class="crud-feedback error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="crud-form">
                @csrf
                <input type="hidden" name="account_type" value="{{ $accountType }}">

                <div class="billing-toggle">
                    <label class="crud-field">
                        <span class="crud-label">Ciclo de facturacion</span>
                        <select class="crud-input" name="billing_cycle" required>
                            <option value="monthly" @selected(old('billing_cycle', 'monthly') === 'monthly')>Mensual</option>
                            <option value="yearly" @selected(old('billing_cycle') === 'yearly')>Anual</option>
                        </select>
                    </label>
                </div>

                <div class="pricing-grid">
                    @foreach ($plans as $plan)
                        <label class="plan-card">
                            <input
                                type="radio"
                                name="plan_id"
                                value="{{ $plan->id }}"
                                @checked((string) old('plan_id') === (string) $plan->id)
                                required
                            >
                            <div>
                                <p class="plan-name">{{ $plan->name }}</p>
                                <p class="plan-price">
                                    <strong>${{ number_format((float) $plan->monthly_price, 2) }}</strong> / mes
                                </p>
                                <p class="plan-alt">
                                    ${{ number_format((float) $plan->yearly_price, 2) }} / ano
                                </p>
                                <ul class="plan-list">
                                    <li>Usuarios: {{ $plan->max_users }}</li>
                                    <li>Sucursales: {{ $plan->max_branches }}</li>
                                    <li>Tickets: {{ $plan->max_tickets }}</li>
                                    <li>IA: {{ $plan->has_ai ? 'Incluida' : 'No incluida' }}</li>
                                    <li>Inventario: {{ $plan->has_inventory ? 'Incluido' : 'No incluido' }}</li>
                                    <li>Reportes: {{ $plan->has_reports ? 'Incluido' : 'No incluido' }}</li>
                                </ul>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="crud-grid">
                    <label class="crud-field">
                        <span class="crud-label">{{ $accountType === 'technician' ? 'Nombre comercial (taller)' : 'Empresa' }}</span>
                        <input class="crud-input" type="text" name="company_name" value="{{ old('company_name') }}" required>
                    </label>

                    <label class="crud-field">
                        <span class="crud-label">Email empresa</span>
                        <input class="crud-input" type="email" name="company_email" value="{{ old('company_email') }}">
                    </label>

                    <label class="crud-field">
                        <span class="crud-label">{{ $accountType === 'technician' ? 'Nombre del tecnico' : 'Nombre propietario' }}</span>
                        <input class="crud-input" type="text" name="owner_name" value="{{ old('owner_name') }}" required>
                    </label>

                    <label class="crud-field">
                        <span class="crud-label">{{ $accountType === 'technician' ? 'Correo del tecnico' : 'Correo propietario' }}</span>
                        <input class="crud-input" type="email" name="owner_email" value="{{ old('owner_email') }}" required>
                    </label>

                    <label class="crud-field">
                        <span class="crud-label">Telefono</span>
                        <input class="crud-input" type="text" name="owner_phone" value="{{ old('owner_phone') }}">
                    </label>

                    <label class="crud-field">
                        <span class="crud-label">Contrasena</span>
                        <input class="crud-input" type="password" name="password" required>
                    </label>

                    <label class="crud-field">
                        <span class="crud-label">Confirmar contrasena</span>
                        <input class="crud-input" type="password" name="password_confirmation" required>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ $accountType === 'technician' ? 'Crear cuenta tecnica' : 'Crear empresa y activar plan' }}
                </button>
            </form>
        </section>
    </main>
@endsection

