@extends('layouts.base')

@section('title', 'IAtechs Pro | Restablecer contrasena')

@section('body')
    <main class="public-hero">
        <section class="public-hero-inner">
            <p class="public-kicker">IAtechs Pro</p>
            <h1>Nueva contrasena</h1>
            <p class="public-summary">
                Define una nueva contrasena segura para continuar usando tu portal.
            </p>
            <div class="public-actions">
                <a href="{{ route('login') }}" class="btn btn-secondary">Volver a iniciar sesion</a>
                <a href="{{ route('public.home') }}" class="btn btn-secondary">Ir al inicio</a>
            </div>
        </section>

        <section class="surface-card auth-card">
            <header class="surface-header">
                <h2>Restablecer contrasena</h2>
            </header>

            @if ($errors->any())
                <div class="crud-feedback error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="crud-form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <label class="crud-field">
                    <span class="crud-label">Correo</span>
                    <input
                        class="crud-input"
                        type="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        required
                        autocomplete="email"
                    >
                </label>

                <label class="crud-field">
                    <span class="crud-label">Nueva contrasena</span>
                    <input
                        class="crud-input"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                    >
                </label>

                <label class="crud-field">
                    <span class="crud-label">Confirmar contrasena</span>
                    <input
                        class="crud-input"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    >
                </label>

                <button type="submit" class="btn btn-primary">Actualizar contrasena</button>
            </form>
        </section>
    </main>
@endsection
