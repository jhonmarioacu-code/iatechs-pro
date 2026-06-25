@extends('layouts.customer')

@section('portal-content')
    <section class="surface-card">
        <header class="surface-header">
            <h2>{{ $ticket->ticket_number }}</h2>
            <a class="btn btn-secondary" href="{{ route('portal.customer.tickets.index') }}">Volver a tickets</a>
        </header>
        <p class="module-copy">
            Estado: <strong>{{ $ticket->status }}</strong> |
            Prioridad: <strong>{{ $ticket->priority }}</strong> |
            Tecnico: <strong>{{ $ticket->technician?->name ?? 'Sin asignar' }}</strong>
        </p>
        <p class="module-copy">Problema reportado: {{ $ticket->reported_problem }}</p>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Diagnosticos</h2>
        </header>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Estado</th>
                        <th>Resultado</th>
                        <th>Costo estimado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticket->diagnostics as $diagnostic)
                        <tr>
                            <td>{{ $diagnostic->diagnostic_code }}</td>
                            <td>{{ $diagnostic->status }}</td>
                            <td>{{ $diagnostic->diagnostic_result ?? 'Pendiente' }}</td>
                            <td>{{ $diagnostic->estimated_cost }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Aun no hay diagnosticos para este ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Reparaciones</h2>
        </header>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Actualizado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticket->repairs as $repair)
                        <tr>
                            <td>{{ $repair->repair_number }}</td>
                            <td>{{ $repair->status }}</td>
                            <td>{{ $repair->total_cost }}</td>
                            <td>{{ optional($repair->updated_at)->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Aun no hay reparaciones para este ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Cotizaciones</h2>
        </header>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Vence</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticket->quotes as $quote)
                        <tr>
                            <td>{{ $quote->quote_number }}</td>
                            <td>{{ $quote->status }}</td>
                            <td>{{ $quote->total }}</td>
                            <td>{{ optional($quote->expires_at)->toDateString() ?? 'N/A' }}</td>
                            <td>
                                @if ($quote->status === 'PENDING_APPROVAL')
                                    <form method="POST" action="{{ route('portal.customer.quotes.approve', $quote) }}" style="display:inline-block;">
                                        @csrf
                                        <button class="btn btn-primary" type="submit">Aprobar</button>
                                    </form>
                                    <form method="POST" action="{{ route('portal.customer.quotes.reject', $quote) }}" style="display:inline-block;">
                                        @csrf
                                        <button class="btn btn-secondary" type="submit">Rechazar</button>
                                    </form>
                                @else
                                    Sin accion
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Aun no hay cotizaciones para este ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
