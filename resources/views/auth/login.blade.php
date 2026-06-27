@extends('layouts.base')

@section('title', 'IAtechs Pro | Iniciar sesion')

@section('body')
    <main class="public-hero">
        <section class="public-hero-inner">
            <p class="public-kicker">IAtechs Pro</p>
            <h1>Acceso seguro al portal</h1>
            <p class="public-summary">
                Inicia sesion y el sistema te enviara automaticamente
                al portal correspondiente segun tu rol.
            </p>

            <div class="public-actions">
                <a href="{{ route('register') }}" class="btn btn-secondary">Registrar empresa</a>
                <a href="{{ route('public.home') }}" class="btn btn-secondary">Volver al inicio</a>
            </div>
        </section>

        <section class="surface-card auth-card">
            <header class="surface-header">
                <h2>Iniciar sesion</h2>
            </header>

            <div class="public-actions" style="margin-bottom: 1rem;">
                <a href="{{ route('auth.social.redirect', ['provider' => 'google']) }}" class="btn btn-secondary">Entrar con Google</a>
                <a href="{{ route('auth.social.redirect', ['provider' => 'microsoft']) }}" class="btn btn-secondary">Entrar con Microsoft</a>
                <a href="{{ route('auth.social.redirect', ['provider' => 'github']) }}" class="btn btn-secondary">Entrar con GitHub</a>
            </div>

            @if (session('status'))
                <div class="crud-feedback success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="crud-feedback error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="crud-form">
                @csrf

                <label class="crud-field">
                    <span class="crud-label">Correo</span>
                    <input
                        class="crud-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                </label>

                <label class="crud-field">
                    <span class="crud-label">Contrasena</span>
                    <input
                        class="crud-input"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >
                </label>

                <label class="crud-checkbox">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    Recordarme
                </label>

                <button type="submit" class="btn btn-primary">Entrar</button>
                <a href="{{ route('password.request') }}" class="btn btn-secondary">Olvide mi contrasena</a>
            </form>
        </section>
    </main>
@endsection
