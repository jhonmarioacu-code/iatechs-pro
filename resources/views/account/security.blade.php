@extends('layouts.base')

@section('title', 'IAtechs Pro | Seguridad de Cuenta')

@section('body')
    <x-portal-shell
        :portal="$portal"
        :title="$portalTitle"
        :subtitle="$portalSubtitle"
        :kpis="$kpis"
    >
        @if (session('ok'))
            <div class="crud-feedback success">{{ session('ok') }}</div>
        @endif

        @if ($errors->any())
            <div class="crud-feedback error">{{ $errors->first() }}</div>
        @endif

        <section class="surface-card">
            <header class="surface-header">
                <h2>Cambiar contrasena</h2>
            </header>

            <form method="POST" action="{{ route('portal.account.security.update') }}" class="crud-form">
                @csrf

                <div class="crud-grid">
                    <label class="crud-field">
                        <span class="crud-label">Contrasena actual</span>
                        <input class="crud-input" type="password" name="current_password" required>
                    </label>

                    <label class="crud-field">
                        <span class="crud-label">Nueva contrasena</span>
                        <input class="crud-input" type="password" name="password" required>
                    </label>

                    <label class="crud-field">
                        <span class="crud-label">Confirmar nueva contrasena</span>
                        <input class="crud-input" type="password" name="password_confirmation" required>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar contrasena</button>
            </form>
        </section>
    </x-portal-shell>
@endsection
