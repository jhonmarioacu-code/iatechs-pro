@extends('layouts.customer')

@section('portal-content')
    @if (session('status'))
        <section class="surface-card">
            <p class="module-copy">{{ session('status') }}</p>
        </section>
    @endif

    <section class="surface-card" x-data="{ area: 'tracking' }">
        <header class="surface-header">
            <h2>Experiencia de cliente</h2>
            <div class="panel-tabs" role="tablist" aria-label="Resumen cliente">
                <button class="panel-tab" :class="{ 'is-active': area === 'tracking' }" type="button" @click="area = 'tracking'">Seguimiento</button>
                <button class="panel-tab" :class="{ 'is-active': area === 'billing' }" type="button" @click="area = 'billing'">Facturacion</button>
                <button class="panel-tab" :class="{ 'is-active': area === 'market' }" type="button" @click="area = 'market'">Marketplace</button>
            </div>
        </header>

        <div x-show="area === 'tracking'" class="tag-grid" x-cloak>
            <span>Tickets abiertos: {{ $latestTickets->whereNotIn('status', ['CLOSED', 'DELIVERED'])->count() }}</span>
            <span>Tickets cerrados: {{ $latestTickets->whereIn('status', ['CLOSED', 'DELIVERED'])->count() }}</span>
            <span>Equipos en reparacion: {{ $latestTickets->where('status', 'IN_REPAIR')->count() }}</span>
            <span>Sin tecnico: {{ $latestTickets->whereNull('technician_id')->count() }}</span>
        </div>

        <div x-show="area === 'billing'" class="tag-grid" x-cloak>
            <span>Facturas pendientes: {{ $pendingInvoicesCount }}</span>
            <span>Pagos realizados: {{ $paidInvoicesCount }}</span>
            <span>Pendiente por pagar: {{ number_format((float) $pendingInvoicesAmount, 2) }}</span>
            <span>Pagado historico: {{ number_format((float) $paidInvoicesAmount, 2) }}</span>
        </div>

        <div x-show="area === 'market'" class="tag-grid" x-cloak>
            <span>Productos disponibles: {{ $marketplaceProducts->count() }}</span>
            <span>Servicios disponibles: {{ $marketplaceServices->count() }}</span>
            <span>Productos destacados: {{ $marketplaceProducts->take(4)->count() }}</span>
            <span>Servicios destacados: {{ $marketplaceServices->take(4)->count() }}</span>
        </div>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Seguimiento rapido</h2>
            <a class="btn btn-secondary" href="{{ route('portal.customer.tickets.index') }}">Ver todos mis tickets</a>
        </header>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Estado</th>
                        <th>Tecnico</th>
                        <th>Equipo</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestTickets as $ticket)
                        <tr>
                            <td>{{ $ticket->ticket_number }}</td>
                            <td>{{ $ticket->status }}</td>
                            <td>{{ $ticket->technician?->name ?? 'Sin asignar' }}</td>
                            <td>{{ $ticket->device?->brand }} {{ $ticket->device?->model }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('portal.customer.tickets.show', $ticket) }}">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No tienes tickets registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Marketplace de la empresa</h2>
            <a class="btn btn-secondary" href="{{ route('portal.customer.marketplace') }}">Ver catalogo completo</a>
        </header>
        <div class="tag-grid">
            @foreach ($marketplaceProducts->take(4) as $product)
                <span>{{ $product->name }} | {{ $product->sale_price }}</span>
            @endforeach
            @foreach ($marketplaceServices->take(4) as $service)
                <span>{{ $service->name }} | {{ $service->status }}</span>
            @endforeach
        </div>
    </section>
@endsection
