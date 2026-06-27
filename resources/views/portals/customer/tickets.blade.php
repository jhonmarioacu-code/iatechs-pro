@extends('layouts.customer')

@section('portal-content')
    <section class="surface-card">
        <header class="surface-header">
            <h2>Mis tickets</h2>
            <a class="btn btn-secondary" href="{{ route('portal.customer.dashboard') }}">Volver al dashboard</a>
        </header>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Tecnico</th>
                        <th>Actualizado</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->ticket_number }}</td>
                            <td>{{ $ticket->status }}</td>
                            <td>{{ $ticket->priority }}</td>
                            <td>{{ $ticket->technician?->name ?? 'Sin asignar' }}</td>
                            <td>{{ optional($ticket->updated_at)->diffForHumans() }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('portal.customer.tickets.show', $ticket) }}">Detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay tickets disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-shell">
            {{ $tickets->links() }}
        </div>
    </section>
@endsection
