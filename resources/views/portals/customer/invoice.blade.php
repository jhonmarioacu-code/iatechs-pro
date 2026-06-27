@extends('layouts.customer')

@section('portal-content')
    <section class="surface-card surface-stack">
        <header class="surface-header">
            <h2>Factura {{ $invoice->invoice_number }}</h2>
            <a class="btn btn-secondary" href="{{ route('portal.customer.invoices.index') }}">Volver a facturas</a>
        </header>

        @if (session('status'))
            <p class="module-copy">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <div class="crud-feedback error">
                <strong>Corrige los siguientes errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="pill-list">
            <span>Estado: {{ $invoice->status }}</span>
            <span>Total: {{ $invoice->total }} {{ $invoice->currency }}</span>
            <span>Pagado: {{ number_format($completedAmount, 2) }} {{ $invoice->currency }}</span>
            <span>Saldo: {{ number_format($remainingAmount, 2) }} {{ $invoice->currency }}</span>
        </div>

        <div class="crud-actions">
            <a class="btn btn-secondary" href="{{ route('portal.customer.invoices.download', $invoice) }}">
                Descargar factura
            </a>
        </div>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Items facturados</h2>
        </header>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay items detallados en esta factura.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Pagar factura</h2>
        </header>
        @if ($remainingAmount > 0)
            <form method="POST" action="{{ route('portal.customer.invoices.pay', $invoice) }}" class="crud-form">
                @csrf
                <div class="crud-grid">
                    <label class="crud-field" for="payment_method">
                        <span class="crud-label">Metodo de pago</span>
                        <select id="payment_method" class="crud-input" name="payment_method" required>
                            <option value="">Seleccionar metodo</option>
                            @foreach (['CARD','BANK_TRANSFER','PSE','NEQUI','DAVIPLATA','PAYPAL','STRIPE','MERCADOPAGO','OTHER'] as $method)
                                <option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ $method }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="crud-field" for="amount">
                        <span class="crud-label">Monto a pagar (max {{ number_format($remainingAmount, 2) }})</span>
                        <input id="amount" class="crud-input" name="amount" type="number" min="0.01" step="0.01" value="{{ old('amount', number_format($remainingAmount, 2, '.', '')) }}" required>
                    </label>

                    <label class="crud-field" for="reference">
                        <span class="crud-label">Referencia (opcional)</span>
                        <input id="reference" class="crud-input" name="reference" type="text" value="{{ old('reference') }}">
                    </label>
                </div>

                <div class="crud-actions">
                    <button class="btn btn-primary" type="submit">Pagar y generar comprobante</button>
                </div>
            </form>
        @else
            <p class="module-copy">Esta factura ya esta totalmente pagada.</p>
        @endif
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Pagos registrados</h2>
        </header>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Comprobante</th>
                        <th>Estado</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_number }}</td>
                            <td>{{ $payment->status }}</td>
                            <td>{{ $payment->amount }} {{ $payment->currency }}</td>
                            <td>{{ optional($payment->paid_at)->toDateTimeString() ?? 'N/A' }}</td>
                            <td>
                                <a class="btn btn-secondary" href="{{ route('portal.customer.payments.receipt', $payment) }}">
                                    Descargar comprobante
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay pagos asociados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
