@extends('layouts.technician')

@section('portal-content')
    <section class="surface-card" x-data="{ mode: 'assigned' }">
        <header class="surface-header">
            <h2>Panel tecnico de ejecucion</h2>
            <div class="panel-tabs" role="tablist" aria-label="Resumen tecnico">
                <button class="panel-tab" :class="{ 'is-active': mode === 'assigned' }" type="button" @click="mode = 'assigned'">Asignados</button>
                <button class="panel-tab" :class="{ 'is-active': mode === 'available' }" type="button" @click="mode = 'available'">Disponibles</button>
                <button class="panel-tab" :class="{ 'is-active': mode === 'repair' }" type="button" @click="mode = 'repair'">Reparacion</button>
            </div>
        </header>

        <div x-show="mode === 'assigned'" class="tag-grid" x-cloak>
            <span>Tickets asignados: {{ $assignedTickets->count() }}</span>
            <span>Pendiente diagnostico: {{ $assignedTickets->where('status', 'PENDING_DIAGNOSIS')->count() }}</span>
            <span>En progreso: {{ $assignedTickets->where('status', 'IN_PROGRESS')->count() }}</span>
            <span>Esperando cliente: {{ $assignedTickets->where('status', 'AWAITING_CUSTOMER_APPROVAL')->count() }}</span>
        </div>

        <div x-show="mode === 'available'" class="tag-grid" x-cloak>
            <span>Abiertos sin tecnico: {{ $availableTickets->count() }}</span>
            <span>Prioridad alta: {{ $availableTickets->where('priority', 'HIGH')->count() }}</span>
            <span>Prioridad media: {{ $availableTickets->where('priority', 'MEDIUM')->count() }}</span>
            <span>Prioridad baja: {{ $availableTickets->where('priority', 'LOW')->count() }}</span>
        </div>

        <div x-show="mode === 'repair'" class="tag-grid" x-cloak>
            <span>Equipos en reparacion: {{ $repairingTickets->count() }}</span>
            <span>Con manual: {{ $repairingTickets->filter(fn ($t) => (bool) $t->device?->manual_url)->count() }}</span>
            <span>Con diagrama: {{ $repairingTickets->filter(fn ($t) => (bool) $t->device?->diagram_url)->count() }}</span>
            <span>Boardview habilitado: {{ $repairingTickets->filter(fn ($t) => (bool) $t->device?->boardview_enabled)->count() }}</span>
        </div>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Cola real de trabajos asignados</h2>
        </header>

        @if (session('status'))
            <p class="module-copy">{{ session('status') }}</p>
        @endif

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Cliente</th>
                        <th>Equipo</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignedTickets as $ticket)
                        <tr>
                            <td>{{ $ticket->ticket_number }}</td>
                            <td>{{ $ticket->customer?->first_name }} {{ $ticket->customer?->last_name }}</td>
                            <td>{{ $ticket->device?->brand }} {{ $ticket->device?->model }}</td>
                            <td>{{ $ticket->status }}</td>
                            <td>{{ $ticket->priority }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('portal.technician.tickets.show', $ticket) }}">
                                    Gestionar flujo
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay tickets asignados para este tecnico.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="surface-grid">
        <section class="surface-card">
            <header class="surface-header">
                <h2>Tickets disponibles para tomar</h2>
            </header>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Sucursal</th>
                            <th>Cliente</th>
                            <th>Equipo</th>
                            <th>Prioridad</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($availableTickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>{{ $ticket->branch?->name ?? 'N/A' }}</td>
                                <td>{{ $ticket->customer?->first_name }} {{ $ticket->customer?->last_name }}</td>
                                <td>{{ $ticket->device?->brand }} {{ $ticket->device?->model }}</td>
                                <td>{{ $ticket->priority }}</td>
                                <td>
                                    <form method="POST" action="{{ route('portal.technician.tickets.take', $ticket) }}">
                                        @csrf
                                        <button class="btn btn-primary" type="submit">Tomar ticket</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No hay tickets abiertos disponibles.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="surface-card">
            <header class="surface-header">
                <h2>Equipos en reparacion</h2>
            </header>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Equipo</th>
                            <th>Manual</th>
                            <th>Diagrama</th>
                            <th>Boardview</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($repairingTickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>{{ $ticket->device?->brand }} {{ $ticket->device?->model }}</td>
                                <td>{{ $ticket->device?->manual_url ? 'Disponible' : 'No cargado' }}</td>
                                <td>{{ $ticket->device?->diagram_url ? 'Disponible' : 'No cargado' }}</td>
                                <td>{{ $ticket->device?->boardview_enabled ? 'Habilitado' : 'No habilitado' }}</td>
                                <td>
                                    <a class="btn btn-primary" href="{{ route('portal.technician.tickets.show', $ticket) }}">
                                        Ver equipo
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No hay equipos actualmente en reparacion.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
