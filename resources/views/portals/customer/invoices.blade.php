@extends('layouts.customer')

@section('portal-content')
    <section class="surface-card">
        <header class="surface-header">
            <h2>Mis facturas</h2>
            <a class="btn btn-secondary" href="{{ route('portal.customer.dashboard') }}">Volver al dashboard</a>
        </header>

        @if (session('status'))
            <p class="module-copy">{{ session('status') }}</p>
        @endif

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Vencimiento</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->status }}</td>
                            <td>{{ $invoice->total }} {{ $invoice->currency }}</td>
                            <td>{{ optional($invoice->due_date)->toDateString() ?? 'N/A' }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('portal.customer.invoices.show', $invoice) }}">Ver factura</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay facturas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-shell">
            {{ $invoices->links() }}
        </div>
    </section>
@endsection
