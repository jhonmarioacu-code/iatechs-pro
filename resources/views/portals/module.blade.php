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
    <section class="surface-card surface-stack">
        <header class="surface-header">
            <h2>{{ $moduleLabel }}</h2>
        </header>

        @if ($portal === 'admin' && $module === 'dashboards' && isset($moduleData['stats']))
            <p class="module-copy">Vista maestra consolidada para Super Admin.</p>
            <div class="pill-list">
                @foreach ($moduleData['stats'] as $stat)
                    <span>{{ $stat['label'] }}: {{ $stat['value'] }}</span>
                @endforeach
            </div>
        @elseif ($portal === 'admin' && $module === 'customers' && isset($moduleData['rows']))
            <p class="module-copy">Clientes multi-tenant visibles para administracion global.</p>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Empresa</th>
                            <th>Sucursal</th>
                            <th>Email</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($moduleData['rows'] as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->display_name }}</td>
                                <td>{{ $customer->company?->name ?? 'N/A' }}</td>
                                <td>{{ $customer->branch?->name ?? 'N/A' }}</td>
                                <td>{{ $customer->email ?? 'N/A' }}</td>
                                <td>{{ $customer->is_active ? 'Activo' : 'Inactivo' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No hay clientes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @elseif ($portal === 'admin' && $module === 'crm' && isset($moduleData['stats']))
            <p class="module-copy">Resumen global de CRM y pipeline comercial.</p>
            <div class="pill-list">
                @foreach ($moduleData['stats'] as $stat)
                    <span>{{ $stat['label'] }}: {{ $stat['value'] }}</span>
                @endforeach
            </div>

            <div class="surface-grid">
                <section class="surface-card">
                    <header class="surface-header"><h3>Leads recientes</h3></header>
                    <ul class="surface-list">
                        @forelse ($moduleData['leads'] as $lead)
                            <li>{{ $lead->name }} | {{ $lead->status }} | {{ $lead->email }}</li>
                        @empty
                            <li>Sin leads.</li>
                        @endforelse
                    </ul>
                </section>

                <section class="surface-card">
                    <header class="surface-header"><h3>Opportunities recientes</h3></header>
                    <ul class="surface-list">
                        @forelse ($moduleData['opportunities'] as $opportunity)
                            <li>{{ $opportunity->title }} | {{ $opportunity->stage }} | {{ $opportunity->amount }}</li>
                        @empty
                            <li>Sin opportunities.</li>
                        @endforelse
                    </ul>
                </section>
            </div>
        @elseif ($portal === 'admin' && $module === 'marketplace' && isset($moduleData['products']))
            <p class="module-copy">Catalogo global de productos y servicios activos.</p>
            <div class="surface-grid">
                <section class="surface-card">
                    <header class="surface-header"><h3>Productos</h3></header>
                    <ul class="surface-list">
                        @forelse ($moduleData['products'] as $product)
                            <li>{{ $product->name }} | {{ $product->sale_price }} | {{ $product->status }}</li>
                        @empty
                            <li>Sin productos activos.</li>
                        @endforelse
                    </ul>
                </section>
                <section class="surface-card">
                    <header class="surface-header"><h3>Servicios</h3></header>
                    <ul class="surface-list">
                        @forelse ($moduleData['services'] as $service)
                            <li>{{ $service->name }} | {{ $service->status }}</li>
                        @empty
                            <li>Sin servicios activos.</li>
                        @endforelse
                    </ul>
                </section>
            </div>
        @elseif (isset($moduleData['stats']) || isset($moduleData['links']))
            <p class="module-copy">Modulo habilitado para operacion. Estado listo para uso en flujo de produccion.</p>
            @if (isset($moduleData['stats']))
                <div class="pill-list">
                    @foreach ($moduleData['stats'] as $stat)
                        <span>{{ $stat['label'] }}: {{ $stat['value'] }}</span>
                    @endforeach
                </div>
            @endif
            @if (isset($moduleData['links']))
                <div class="crud-actions">
                    @foreach ($moduleData['links'] as $link)
                        <a class="btn btn-secondary" href="{{ $link['href'] }}">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            @endif
        @else
            <p class="module-copy">
                Este modulo esta integrado al portal. Si no ves registros aun, valida permisos del rol y datos de prueba.
            </p>
            <p class="module-copy">
                Portal: <strong>{{ $portal }}</strong> | Modulo: <strong>{{ $module }}</strong>
            </p>
        @endif

        @if ($query !== [])
            <pre class="query-box">{{ json_encode($query, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        @endif
    </section>
@endsection
