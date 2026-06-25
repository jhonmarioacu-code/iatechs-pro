@php
    $layout = match ($portal) {
        'admin' => 'layouts.admin',
        'company' => 'layouts.company',
        'technician' => 'layouts.technician',
        'customer' => 'layouts.customer',
        default => 'layouts.admin',
    };
@endphp

@extends($layout)

@section('portal-content')
    <section class="surface-card">
        <header class="surface-header">
            <h2>{{ $moduleLabel }}</h2>
        </header>
        <p class="module-copy">
            Este módulo está preparado con arquitectura frontend unificada (Blade + Tailwind + Alpine)
            y navegación por portal. La implementación funcional de reglas de negocio vive en los
            controladores/servicios por dominio.
        </p>
        <p class="module-copy">
            Portal: <strong>{{ $portal }}</strong>
            | Módulo: <strong>{{ $module }}</strong>
        </p>
        @if ($query !== [])
            <pre class="query-box">{{ json_encode($query, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        @endif
    </section>
@endsection

