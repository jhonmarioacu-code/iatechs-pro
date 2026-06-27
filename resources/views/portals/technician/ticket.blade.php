@extends('layouts.technician')

@section('portal-content')
    <section class="surface-card">
        <header class="surface-header">
            <h2>{{ $ticket->ticket_number }} | Flujo tecnico</h2>
            <a class="btn btn-secondary" href="{{ route('portal.technician.dashboard') }}">Volver a cola</a>
        </header>

        @if (session('status'))
            <p class="module-copy">{{ session('status') }}</p>
        @endif

        @if (session('warning'))
            <p class="module-copy">{{ session('warning') }}</p>
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

        <p class="module-copy">
            Estado ticket: <strong>{{ $ticket->status }}</strong> |
            Prioridad: <strong>{{ $ticket->priority }}</strong>
        </p>

        <p class="module-copy">
            Problema reportado: {{ $ticket->reported_problem }}
        </p>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>Equipo en reparacion: manual, diagrama y boardview</h2>
        </header>

        <p class="module-copy">
            Equipo: <strong>{{ $ticket->device?->brand }} {{ $ticket->device?->model }}</strong> |
            Serial: <strong>{{ $ticket->device?->serial_number ?? 'N/A' }}</strong>
        </p>

        <form method="POST" action="{{ route('portal.technician.tickets.repair-assets.update', $ticket) }}" class="crud-form">
            @csrf
            <div class="crud-grid">
                <label class="crud-field" for="manual_url">
                    <span class="crud-label">URL manual tecnico</span>
                    <input id="manual_url" class="crud-input" type="url" name="manual_url" value="{{ old('manual_url', $ticket->device?->manual_url) }}">
                </label>

                <label class="crud-field" for="diagram_url">
                    <span class="crud-label">URL diagrama</span>
                    <input id="diagram_url" class="crud-input" type="url" name="diagram_url" value="{{ old('diagram_url', $ticket->device?->diagram_url) }}">
                </label>

                <label class="crud-field" for="boardview_url">
                    <span class="crud-label">URL boardview</span>
                    <input id="boardview_url" class="crud-input" type="url" name="boardview_url" value="{{ old('boardview_url', $ticket->device?->boardview_url) }}">
                </label>
            </div>

            <label class="crud-checkbox">
                <input type="checkbox" name="boardview_enabled" value="1" @checked((bool) old('boardview_enabled', $ticket->device?->boardview_enabled))>
                Habilitar boardview para este equipo reparado
            </label>

            <div class="crud-actions">
                <button class="btn btn-primary" type="submit">Guardar documentacion tecnica</button>
            </div>
        </form>

        <p class="module-copy">
            Manual:
            @if ($ticket->device?->manual_url)
                <a href="{{ $ticket->device->manual_url }}" target="_blank" rel="noopener noreferrer">Abrir manual</a>
            @else
                No disponible
            @endif
        </p>
        <p class="module-copy">
            Diagrama:
            @if ($ticket->device?->diagram_url)
                <a href="{{ $ticket->device->diagram_url }}" target="_blank" rel="noopener noreferrer">Abrir diagrama</a>
            @else
                No disponible
            @endif
        </p>
        <p class="module-copy">
            Boardview:
            @if ($ticket->device?->boardview_enabled && $ticket->device?->boardview_url)
                <a href="{{ $ticket->device->boardview_url }}" target="_blank" rel="noopener noreferrer">Abrir boardview</a>
            @elseif ($ticket->device?->boardview_url)
                Cargado pero no habilitado
            @else
                No disponible
            @endif
        </p>
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>1) Diagnostico</h2>
        </header>

        @if (!$diagnostic)
            <form method="POST" action="{{ route('portal.technician.tickets.diagnostics.store', $ticket) }}" class="crud-form">
                @csrf
                <label class="crud-field" for="reported_problem">
                    <span class="crud-label">Detalle tecnico inicial</span>
                    <textarea id="reported_problem" class="crud-input" name="reported_problem" rows="4">{{ old('reported_problem', $ticket->reported_problem) }}</textarea>
                </label>
                <div class="crud-actions">
                    <button class="btn btn-primary" type="submit">Crear diagnostico</button>
                </div>
            </form>
        @else
            <p class="module-copy">
                Diagnostico: <strong>{{ $diagnostic->diagnostic_code }}</strong> |
                Estado: <strong>{{ $diagnostic->status }}</strong>
            </p>

            @if ($diagnostic->status === 'PENDING')
                <form method="POST" action="{{ route('portal.technician.diagnostics.start', $diagnostic) }}">
                    @csrf
                    <button class="btn btn-primary" type="submit">Iniciar diagnostico</button>
                </form>
            @endif

            @if ($diagnostic->status === 'IN_PROGRESS')
                <form method="POST" action="{{ route('portal.technician.diagnostics.complete', $diagnostic) }}" class="crud-form">
                    @csrf
                    <div class="crud-grid">
                        <label class="crud-field" for="diagnostic_result">
                            <span class="crud-label">Resultado diagnostico</span>
                            <textarea id="diagnostic_result" class="crud-input" name="diagnostic_result" rows="3">{{ old('diagnostic_result') }}</textarea>
                        </label>

                        <label class="crud-field" for="recommended_solution">
                            <span class="crud-label">Solucion recomendada</span>
                            <textarea id="recommended_solution" class="crud-input" name="recommended_solution" rows="3">{{ old('recommended_solution') }}</textarea>
                        </label>

                        <label class="crud-field" for="estimated_cost">
                            <span class="crud-label">Costo estimado</span>
                            <input id="estimated_cost" class="crud-input" type="number" step="0.01" min="0" name="estimated_cost" value="{{ old('estimated_cost', 0) }}">
                        </label>

                        <label class="crud-field" for="estimated_hours">
                            <span class="crud-label">Horas estimadas</span>
                            <input id="estimated_hours" class="crud-input" type="number" min="0" name="estimated_hours" value="{{ old('estimated_hours', 0) }}">
                        </label>
                    </div>

                    <div class="crud-actions">
                        <button class="btn btn-primary" type="submit">Completar diagnostico</button>
                    </div>
                </form>
            @endif
        @endif
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>2) Cotizacion para aprobacion</h2>
        </header>

        @if ($diagnostic && $diagnostic->status === 'COMPLETED')
            <p class="module-copy">
                Estado actual:
                <strong>{{ $pendingQuote?->status ?? 'SIN COTIZACION' }}</strong>
            </p>
            <form method="POST" action="{{ route('portal.technician.tickets.quotes.submit', $ticket) }}" class="crud-form">
                @csrf
                <div class="crud-grid">
                    <label class="crud-field" for="subtotal">
                        <span class="crud-label">Subtotal cotizacion</span>
                        <input id="subtotal" class="crud-input" type="number" step="0.01" min="0" name="subtotal" value="{{ old('subtotal', $pendingQuote?->subtotal ?? $diagnostic->estimated_cost ?? 0) }}" required>
                    </label>

                    <label class="crud-field" for="tax">
                        <span class="crud-label">Impuesto</span>
                        <input id="tax" class="crud-input" type="number" step="0.01" min="0" name="tax" value="{{ old('tax', $pendingQuote?->tax ?? 0) }}">
                    </label>

                    <label class="crud-field" for="discount">
                        <span class="crud-label">Descuento</span>
                        <input id="discount" class="crud-input" type="number" step="0.01" min="0" name="discount" value="{{ old('discount', $pendingQuote?->discount ?? 0) }}">
                    </label>

                    <label class="crud-field" for="expires_at">
                        <span class="crud-label">Vigencia</span>
                        <input id="expires_at" class="crud-input" type="date" name="expires_at" value="{{ old('expires_at', optional($pendingQuote?->expires_at)->toDateString()) }}">
                    </label>

                    <label class="crud-field" for="notes">
                        <span class="crud-label">Notas para cliente</span>
                        <textarea id="notes" class="crud-input" name="notes" rows="3">{{ old('notes', $pendingQuote?->notes) }}</textarea>
                    </label>
                </div>

                <p class="module-copy"><strong>Canales de notificacion</strong></p>
                <label class="crud-checkbox">
                    <input type="checkbox" name="channels[]" value="EMAIL" @checked(in_array('EMAIL', old('channels', ['EMAIL', 'SMS', 'WHATSAPP']), true))>
                    Correo
                </label>
                <label class="crud-checkbox">
                    <input type="checkbox" name="channels[]" value="SMS" @checked(in_array('SMS', old('channels', ['EMAIL', 'SMS', 'WHATSAPP']), true))>
                    SMS
                </label>
                <label class="crud-checkbox">
                    <input type="checkbox" name="channels[]" value="WHATSAPP" @checked(in_array('WHATSAPP', old('channels', ['EMAIL', 'SMS', 'WHATSAPP']), true))>
                    WhatsApp
                </label>

                <div class="crud-actions">
                    <button class="btn btn-primary" type="submit">Enviar cotizacion a cliente</button>
                </div>
            </form>
        @else
            <p class="module-copy">Completa el diagnostico para habilitar la cotizacion.</p>
        @endif
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>3) Reparacion</h2>
        </header>

        @if (!$repair)
            <form method="POST" action="{{ route('portal.technician.tickets.repairs.store', $ticket) }}" class="crud-form">
                @csrf
                <div class="crud-grid">
                    <label class="crud-field" for="quote_id">
                        <span class="crud-label">Cotizacion aprobada</span>
                        <select id="quote_id" class="crud-input" name="quote_id" required>
                            <option value="">Seleccionar cotizacion</option>
                            @foreach ($approvedQuotes as $quote)
                                <option value="{{ $quote->id }}" @selected((int) old('quote_id') === $quote->id)>
                                    {{ $quote->quote_number }} ({{ $quote->status }})
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="crud-field" for="repair_notes">
                        <span class="crud-label">Notas de reparacion</span>
                        <textarea id="repair_notes" class="crud-input" name="repair_notes" rows="3">{{ old('repair_notes') }}</textarea>
                    </label>

                    <label class="crud-field" for="labor_cost">
                        <span class="crud-label">Costo mano de obra</span>
                        <input id="labor_cost" class="crud-input" type="number" step="0.01" min="0" name="labor_cost" value="{{ old('labor_cost', 0) }}">
                    </label>

                    <label class="crud-field" for="parts_cost">
                        <span class="crud-label">Costo repuestos</span>
                        <input id="parts_cost" class="crud-input" type="number" step="0.01" min="0" name="parts_cost" value="{{ old('parts_cost', 0) }}">
                    </label>
                </div>

                <div class="crud-actions">
                    <button class="btn btn-primary" type="submit">Crear reparacion</button>
                </div>
            </form>
        @else
            <p class="module-copy">
                Reparacion: <strong>{{ $repair->repair_number }}</strong> |
                Estado: <strong>{{ $repair->status }}</strong>
            </p>

            @if (in_array($repair->status, ['PENDING', 'ASSIGNED'], true))
                <form method="POST" action="{{ route('portal.technician.repairs.start', $repair) }}">
                    @csrf
                    <button class="btn btn-primary" type="submit">Iniciar reparacion</button>
                </form>
            @endif

            @if ($repair->status === 'IN_PROGRESS')
                <form method="POST" action="{{ route('portal.technician.repairs.complete', $repair) }}">
                    @csrf
                    <button class="btn btn-primary" type="submit">Completar reparacion</button>
                </form>
            @endif
        @endif
    </section>

    <section class="surface-card">
        <header class="surface-header">
            <h2>4) Cierre de ticket</h2>
        </header>
        <form method="POST" action="{{ route('portal.technician.tickets.close', $ticket) }}">
            @csrf
            <button class="btn btn-primary" type="submit">Cerrar ticket</button>
        </form>
    </section>
@endsection
