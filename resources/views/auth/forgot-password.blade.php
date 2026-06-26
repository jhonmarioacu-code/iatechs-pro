@extends('layouts.base')

@section('title', 'IAtechs Pro | Recuperar contrasena')

@section('body')
    <main class="public-hero">
        <section class="public-hero-inner">
            <p class="public-kicker">IAtechs Pro</p>
            <h1>Recuperar acceso</h1>
            <p class="public-summary">
                Ingresa tu correo y te enviaremos un enlace seguro para restablecer tu contrasena.
            </p>
        </section>

        <section class="surface-card auth-card">
            <header class="surface-header">
                <h2>Recuperacion de contrasena</h2>
            </header>

            @if (session('status'))
                <div class="crud-feedback success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="crud-feedback error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="crud-form">
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

                <button type="submit" class="btn btn-primary">Enviar enlace</button>
                <a href="{{ route('login') }}" class="btn btn-secondary">Volver a iniciar sesion</a>
            </form>
        </section>
    </main>
@endsection
