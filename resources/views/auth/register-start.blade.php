@extends('layouts.base')

@section('title', 'IAtechs Pro | Onboarding')

@section('body')
    <main class="public-hero">
        <section class="public-hero-inner">
            <p class="public-kicker">IAtechs Pro</p>
            <h1>Comienza tu onboarding profesional</h1>
            <p class="public-summary">
                Elige tu perfil de acceso antes de usar la plataforma. Luego seleccionas el plan y la suscripcion
                para activar solo los modulos contratados.
            </p>

            <div class="public-actions">
                <a href="{{ route('login') }}" class="btn btn-secondary">Ya tengo cuenta</a>
                <a href="{{ route('public.home') }}" class="btn btn-secondary">Volver al inicio</a>
            </div>
        </section>

        <section class="surface-card auth-card onboarding-card">
            <header class="surface-header">
                <h2>Selecciona tu tipo de registro</h2>
            </header>

            <form method="POST" action="{{ route('register.store') }}" class="hidden" aria-hidden="true">
                @csrf
            </form>

            <div class="role-picker">
                <article class="role-tile">
                    <h3>Empresa</h3>
                    <p>Portal empresarial completo para administrar equipo, clientes, tickets, inventario y finanzas.</p>
                    <a href="{{ route('register.plans', ['account_type' => 'company']) }}" class="btn btn-primary">
                        Registrar empresa
                    </a>
                </article>

                <article class="role-tile">
                    <h3>Tecnico independiente</h3>
                    <p>Acceso especializado para tecnicos que operan su propio flujo de diagnostico y reparacion.</p>
                    <a href="{{ route('register.plans', ['account_type' => 'technician']) }}" class="btn btn-secondary">
                        Registrar tecnico
                    </a>
                </article>
            </div>
        </section>
    </main>
@endsection
