@extends('layouts.base')

@section('title', 'IAtechs Pro | Plataforma Empresarial')

@section('body')
    <main class="public-hero">
        <section class="public-hero-inner">
            <p class="public-kicker">IAtechs Pro</p>
            <h1>Plataforma empresarial inteligente para gestion de servicios tecnicos</h1>
            <p class="public-summary">
                Arquitectura SaaS multi-tenant con portales especializados para administracion global,
                operacion empresarial, tecnicos de campo y clientes.
            </p>

            <div class="public-actions">
                @auth
                    <a href="{{ route('portal.home') }}" class="btn btn-primary">Ir a mi portal</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesion</a>
                    <a href="{{ route('register.plans', ['account_type' => 'company']) }}" class="btn btn-secondary">Registrar empresa</a>
                    <a href="{{ route('register.plans', ['account_type' => 'technician']) }}" class="btn btn-secondary">Registrar tecnico</a>
                @endauth
            </div>
        </section>

        <section class="public-health surface-card">
            <h2>Estado de plataforma</h2>
            <div class="health-grid">
                <article>
                    <span>Aplicacion</span>
                    <strong data-health-field="app">Pendiente</strong>
                    <small data-health-row="web">/health</small>
                </article>
                <article>
                    <span>API</span>
                    <strong data-health-field="api">Pendiente</strong>
                    <small data-health-row="api">/api/health</small>
                </article>
            </div>
            <button class="btn btn-secondary" type="button" data-health-refresh>Actualizar estado</button>
        </section>
    </main>
@endsection
