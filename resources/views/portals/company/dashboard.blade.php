@extends('layouts.company')

@section('portal-content')
    @if (session('status'))
        <div class="crud-feedback success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="crud-feedback error">{{ $errors->first() }}</div>
    @endif

    <div class="surface-grid">
        <section class="surface-card">
            <header class="surface-header">
                <h2>Sucursales de la Empresa</h2>
            </header>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Sucursal</th>
                            <th>Codigo</th>
                            <th>Ciudad</th>
                            <th>Pais</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branches as $branch)
                            <tr>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->code }}</td>
                                <td>{{ $branch->city ?? 'N/A' }}</td>
                                <td>{{ $branch->country ?? 'N/A' }}</td>
                                <td>{{ $branch->is_active ? 'Activa' : 'Inactiva' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No hay sucursales registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Gestion de Tickets por Estado</h2>
        </header>

        @foreach ($ticketBoard as $label => $tickets)
            <h3>{{ $label }}</h3>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Cliente</th>
                            <th>Equipo</th>
                            <th>Sucursal</th>
                            <th>Tecnico</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>{{ $ticket->customer?->display_name ?? ($ticket->customer?->first_name.' '.$ticket->customer?->last_name) }}</td>
                                <td>{{ $ticket->device?->brand }} {{ $ticket->device?->model }}</td>
                                <td>{{ $ticket->branch?->name ?? 'N/A' }}</td>
                                <td>{{ $ticket->technician?->name ?? 'Sin asignar' }}</td>
                                <td>{{ $ticket->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">Sin tickets en este estado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endforeach
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Personal, Funciones, Roles y Sucursal</h2>
        </header>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Funcion</th>
                        <th>Sucursal</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($personnel as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->roles->pluck('name')->join(', ') ?: 'Sin rol' }}</td>
                            <td>{{ $member->roles->pluck('name')->join(', ') ?: 'Sin funcion' }}</td>
                            <td>{{ $member->branch?->name ?? 'Sin asignar' }}</td>
                            <td>{{ $member->is_active ? 'Activo' : 'Inactivo' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay personal registrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Habilitar Cursos y Examenes a Tecnicos</h2>
        </header>

        @forelse ($technicians as $technician)
            <form class="crud-form" method="POST" action="{{ route('portal.company.technicians.training.update', $technician) }}">
                @csrf
                <p class="module-copy">
                    <strong>{{ $technician->name }}</strong> ({{ $technician->email }}) |
                    Sucursal: {{ $technician->branch?->name ?? 'Sin asignar' }}
                </p>

                <div class="crud-grid">
                    <label class="crud-checkbox">
                        <input type="checkbox" name="technician_course_enabled" value="1" @checked($technician->technician_course_enabled)>
                        Curso habilitado
                    </label>
                    <label class="crud-checkbox">
                        <input type="checkbox" name="technician_exam_enabled" value="1" @checked($technician->technician_exam_enabled)>
                        Examen habilitado
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar acceso formativo</button>
            </form>
            <hr>
        @empty
            <p class="module-copy">No hay tecnicos para habilitar cursos/examenes.</p>
        @endforelse
    </section>
@endsection
