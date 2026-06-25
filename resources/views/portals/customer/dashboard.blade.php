@extends('layouts.customer')

@section('portal-content')
    @if (session('status'))
        <section class="surface-card">
            <p class="module-copy">{{ session('status') }}</p>
        </section>
    @endif

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
