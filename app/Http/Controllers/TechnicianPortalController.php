<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Customers\Models\Customer;
use App\Domains\Devices\Models\Device;
use App\Domains\Notifications\Services\NotificationService;
use App\Domains\Quotes\Services\QuoteService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Domains\Quotes\Models\Quote;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Repairs\Models\Repair;
use App\Domains\Diagnostics\Models\Diagnostic;
use App\Domains\Tickets\Services\TicketService;
use App\Domains\Repairs\Services\RepairService;
use App\Domains\Diagnostics\Services\DiagnosticService;
use App\Domains\Users\Models\User;

class TechnicianPortalController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly DiagnosticService $diagnosticService,
        private readonly RepairService $repairService,
        private readonly QuoteService $quoteService,
        private readonly NotificationService $notificationService
    ) {}

    public function dashboard(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $baseQuery = Ticket::query()
            ->where('technician_id', $user->id);

        $assignedCount = (clone $baseQuery)
            ->whereIn('status', ['ASSIGNED', 'IN_DIAGNOSIS', 'WAITING_QUOTE', 'APPROVED', 'IN_REPAIR', 'READY_DELIVERY'])
            ->count();

        $diagnosticsPendingCount = Diagnostic::query()
            ->where('technician_id', $user->id)
            ->whereIn('status', ['PENDING', 'IN_PROGRESS'])
            ->count();

        $repairsInProgressCount = Repair::query()
            ->where('technician_id', $user->id)
            ->whereIn('status', ['ASSIGNED', 'IN_PROGRESS', 'WAITING_PARTS'])
            ->count();

        $readyToCloseCount = (clone $baseQuery)
            ->where('status', 'READY_DELIVERY')
            ->count();

        $assignedTickets = (clone $baseQuery)
            ->with(['customer', 'device'])
            ->latest()
            ->limit(20)
            ->get();

        $availableTickets = Ticket::query()
            ->where('company_id', $user->company_id)
            ->whereNull('technician_id')
            ->where('status', 'OPEN')
            ->with(['customer', 'device', 'branch'])
            ->latest()
            ->limit(20)
            ->get();

        $repairingTickets = (clone $baseQuery)
            ->where('status', 'IN_REPAIR')
            ->with(['customer', 'device'])
            ->latest()
            ->limit(20)
            ->get();

        return view('portals.technician.dashboard', [
            'portalTitle' => 'Technician Portal',
            'portalSubtitle' => 'Flujo operativo real: asignadas -> diagnostico -> reparacion -> cierre.',
            'kpis' => [
                ['label' => 'Asignadas activas', 'value' => (string) $assignedCount, 'trend' => 'En tiempo real'],
                ['label' => 'Diagnosticos pendientes', 'value' => (string) $diagnosticsPendingCount, 'trend' => 'En tiempo real'],
                ['label' => 'Reparaciones activas', 'value' => (string) $repairsInProgressCount, 'trend' => 'En tiempo real'],
                ['label' => 'Listas para cierre', 'value' => (string) $readyToCloseCount, 'trend' => 'En tiempo real'],
            ],
            'assignedTickets' => $assignedTickets,
            'availableTickets' => $availableTickets,
            'repairingTickets' => $repairingTickets,
        ]);
    }

    public function showTicket(Request $request, Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        /** @var User $user */
        $user = $request->user();

        $this->ensureTicketAssignedToTechnician($ticket, $user);

        $ticket->load(['customer', 'device', 'branch']);

        $diagnostic = Diagnostic::query()
            ->where('ticket_id', $ticket->id)
            ->where('technician_id', $user->id)
            ->latest()
            ->first();

        $repair = Repair::query()
            ->where('ticket_id', $ticket->id)
            ->where('technician_id', $user->id)
            ->latest()
            ->first();

        $approvedQuotes = Quote::query()
            ->where('company_id', $user->company_id)
            ->where('ticket_id', $ticket->id)
            ->where('status', 'APPROVED')
            ->latest()
            ->get();

        $pendingQuote = Quote::query()
            ->where('company_id', $user->company_id)
            ->where('ticket_id', $ticket->id)
            ->whereIn('status', ['DRAFT', 'PENDING_APPROVAL'])
            ->latest()
            ->first();

        return view('portals.technician.ticket', [
            'portalTitle' => 'Ticket '.$ticket->ticket_number,
            'portalSubtitle' => 'Ejecucion tecnica con acciones auditables.',
            'ticket' => $ticket,
            'diagnostic' => $diagnostic,
            'repair' => $repair,
            'pendingQuote' => $pendingQuote,
            'approvedQuotes' => $approvedQuotes,
        ]);
    }

    public function storeDiagnostic(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('create', Diagnostic::class);

        /** @var User $user */
        $user = $request->user();

        $this->ensureTicketAssignedToTechnician($ticket, $user);

        $validated = $request->validate([
            'reported_problem' => ['required', 'string', 'min:10'],
        ]);

        $activeDiagnosticExists = Diagnostic::query()
            ->where('ticket_id', $ticket->id)
            ->where('technician_id', $user->id)
            ->whereIn('status', ['PENDING', 'IN_PROGRESS'])
            ->exists();

        if ($activeDiagnosticExists) {
            return back()
                ->withErrors(['reported_problem' => 'Ya existe un diagnostico activo para este ticket.'])
                ->withInput();
        }

        $this->diagnosticService->create([
            'company_id' => $ticket->company_id,
            'branch_id' => $ticket->branch_id,
            'ticket_id' => $ticket->id,
            'technician_id' => $user->id,
            'reported_problem' => $validated['reported_problem'],
        ]);

        if ($ticket->status === 'ASSIGNED') {
            $this->ticketService->changeStatus($ticket, 'IN_DIAGNOSIS');
        }

        return back()->with('status', 'Diagnostico creado correctamente.');
    }

    public function startDiagnostic(Request $request, Diagnostic $diagnostic): RedirectResponse
    {
        $this->authorize('start', $diagnostic);

        /** @var User $user */
        $user = $request->user();

        $this->ensureDiagnosticOwnedByTechnician($diagnostic, $user);

        $this->diagnosticService->start($diagnostic);
        $this->ticketService->changeStatus($diagnostic->ticket, 'IN_DIAGNOSIS');

        return back()->with('status', 'Diagnostico iniciado.');
    }

    public function completeDiagnostic(Request $request, Diagnostic $diagnostic): RedirectResponse
    {
        $this->authorize('complete', $diagnostic);

        /** @var User $user */
        $user = $request->user();

        $this->ensureDiagnosticOwnedByTechnician($diagnostic, $user);

        $validated = $request->validate([
            'diagnostic_result' => ['required', 'string', 'min:10'],
            'recommended_solution' => ['required', 'string', 'min:10'],
            'estimated_cost' => ['required', 'numeric', 'min:0'],
            'estimated_hours' => ['required', 'integer', 'min:0'],
        ]);

        $this->diagnosticService->complete($diagnostic, $validated);
        $this->ticketService->changeStatus($diagnostic->ticket, 'WAITING_QUOTE');

        return back()->with('status', 'Diagnostico completado.');
    }

    public function storeRepair(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('create', Repair::class);

        /** @var User $user */
        $user = $request->user();

        $this->ensureTicketAssignedToTechnician($ticket, $user);

        $validated = $request->validate([
            'quote_id' => [
                'required',
                Rule::exists('quotes', 'id')->where(static function ($query) use ($ticket, $user) {
                    $query
                        ->where('ticket_id', $ticket->id)
                        ->where('company_id', $user->company_id)
                        ->where('status', 'APPROVED');
                }),
            ],
            'repair_notes' => ['nullable', 'string'],
            'labor_cost' => ['nullable', 'numeric', 'min:0'],
            'parts_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $diagnostic = Diagnostic::query()
            ->where('ticket_id', $ticket->id)
            ->where('technician_id', $user->id)
            ->where('status', 'COMPLETED')
            ->latest()
            ->first();

        if (!$diagnostic) {
            return back()
                ->withErrors(['quote_id' => 'Debes completar un diagnostico antes de crear la reparacion.'])
                ->withInput();
        }

        $repair = $this->repairService->create([
            'company_id' => $ticket->company_id,
            'branch_id' => $ticket->branch_id,
            'ticket_id' => $ticket->id,
            'diagnostic_id' => $diagnostic->id,
            'quote_id' => (int) $validated['quote_id'],
            'technician_id' => $user->id,
            'repair_notes' => $validated['repair_notes'] ?? null,
            'labor_cost' => $validated['labor_cost'] ?? null,
            'parts_cost' => $validated['parts_cost'] ?? null,
        ]);

        $this->repairService->assign($repair, $user->id);
        $this->ticketService->changeStatus($ticket, 'IN_REPAIR');

        return back()->with('status', 'Reparacion creada y asignada.');
    }

    public function startRepair(Request $request, Repair $repair): RedirectResponse
    {
        $this->authorize('start', $repair);

        /** @var User $user */
        $user = $request->user();

        $this->ensureRepairOwnedByTechnician($repair, $user);

        $this->repairService->start($repair);
        $this->ticketService->changeStatus($repair->ticket, 'IN_REPAIR');

        return back()->with('status', 'Reparacion iniciada.');
    }

    public function completeRepair(Request $request, Repair $repair): RedirectResponse
    {
        $this->authorize('complete', $repair);

        /** @var User $user */
        $user = $request->user();

        $this->ensureRepairOwnedByTechnician($repair, $user);

        $this->repairService->complete($repair);
        $this->ticketService->changeStatus($repair->ticket, 'READY_DELIVERY');

        return back()->with('status', 'Reparacion completada.');
    }

    public function closeTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('close', $ticket);

        /** @var User $user */
        $user = $request->user();

        $this->ensureTicketAssignedToTechnician($ticket, $user);

        if ($ticket->status !== 'READY_DELIVERY') {
            return back()->withErrors([
                'ticket' => 'El ticket solo se puede cerrar cuando la reparacion esta completada o lista para entrega.',
            ]);
        }

        $this->ticketService->closeTicket($ticket);

        return redirect()
            ->route('portal.technician.dashboard')
            ->with('status', 'Ticket cerrado correctamente.');
    }

    public function takeTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($user->can('tickets.update'), 403);
        abort_if($ticket->company_id !== $user->company_id, 403);

        if ($ticket->technician_id !== null) {
            return back()->withErrors([
                'ticket' => 'Este ticket ya fue tomado por otro tecnico.',
            ]);
        }

        if ($ticket->status !== 'OPEN') {
            return back()->withErrors([
                'ticket' => 'Solo se pueden tomar tickets en estado OPEN.',
            ]);
        }

        $this->ticketService->assignTechnician($ticket, $user->id);

        return redirect()
            ->route('portal.technician.tickets.show', $ticket)
            ->with('status', 'Ticket tomado y asignado correctamente.');
    }

    public function submitQuote(Request $request, Ticket $ticket): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless(
            $user->can('quotes.create') || $user->can('quotes.update'),
            403
        );

        $this->ensureTicketAssignedToTechnician($ticket, $user);

        $validated = $request->validate([
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => ['string', Rule::in(['EMAIL', 'SMS', 'WHATSAPP'])],
        ]);

        $diagnostic = Diagnostic::query()
            ->where('ticket_id', $ticket->id)
            ->where('company_id', $user->company_id)
            ->where('technician_id', $user->id)
            ->where('status', 'COMPLETED')
            ->latest()
            ->first();

        if (!$diagnostic) {
            return back()
                ->withErrors(['subtotal' => 'Debes completar un diagnostico antes de enviar cotizacion.'])
                ->withInput();
        }

        $warning = null;

        DB::transaction(function () use ($ticket, $user, $validated, $diagnostic, &$warning): void {
            $quote = Quote::query()
                ->where('company_id', $user->company_id)
                ->where('ticket_id', $ticket->id)
                ->where('diagnostic_id', $diagnostic->id)
                ->whereIn('status', ['DRAFT', 'PENDING_APPROVAL'])
                ->latest()
                ->first();

            $quoteData = [
                'company_id' => $ticket->company_id,
                'branch_id' => $ticket->branch_id,
                'ticket_id' => $ticket->id,
                'diagnostic_id' => $diagnostic->id,
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'expires_at' => $validated['expires_at'] ?? null,
            ];

            if ($quote) {
                $quote = $this->quoteService->update($quote, $quoteData);
            } else {
                $quote = $this->quoteService->create($quoteData);
            }

            $quote = $this->quoteService->sendForApproval($quote);

            $this->ticketService->changeStatus($ticket, 'WAITING_QUOTE');

            $customer = Customer::query()
                ->where('company_id', $user->company_id)
                ->find($ticket->customer_id);

            if (!$customer) {
                return;
            }

            $unroutableChannels = [];

            foreach ($validated['channels'] as $channel) {
                $recipient = $this->resolveRecipient($customer, $channel);

                if ($recipient === null) {
                    $unroutableChannels[] = $channel;
                    continue;
                }

                $this->notificationService->create([
                    'company_id' => $user->company_id,
                    'title' => 'Cotizacion pendiente de aprobacion',
                    'message' => "Ticket {$ticket->ticket_number}: revisa y aprueba la cotizacion {$quote->quote_number}.",
                    'type' => 'quote_ready',
                    'channel' => $channel,
                    'recipient' => $recipient,
                    'subject' => "Cotizacion {$quote->quote_number}",
                    'data' => [
                        'ticket_id' => $ticket->id,
                        'ticket_number' => $ticket->ticket_number,
                        'quote_id' => $quote->id,
                        'quote_number' => $quote->quote_number,
                    ],
                ]);
            }

            if ($unroutableChannels !== []) {
                $warning = 'No se enviaron canales sin contacto del cliente: '.implode(', ', $unroutableChannels).'.';
            }
        });

        return back()->with([
            'status' => 'Cotizacion enviada para aprobacion del cliente.',
            'warning' => $warning,
        ]);
    }

    public function updateRepairAssets(Request $request, Ticket $ticket): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->ensureTicketAssignedToTechnician($ticket, $user);

        $validated = $request->validate([
            'manual_url' => ['nullable', 'url', 'max:2048'],
            'diagram_url' => ['nullable', 'url', 'max:2048'],
            'boardview_url' => ['nullable', 'url', 'max:2048'],
            'boardview_enabled' => ['nullable', 'boolean'],
        ]);

        /** @var Device|null $device */
        $device = $ticket->device;

        if (!$device || $device->company_id !== $user->company_id) {
            abort(404);
        }

        $device->update([
            'manual_url' => $validated['manual_url'] ?? null,
            'diagram_url' => $validated['diagram_url'] ?? null,
            'boardview_url' => $validated['boardview_url'] ?? null,
            'boardview_enabled' => (bool) ($validated['boardview_enabled'] ?? false),
        ]);

        return back()->with('status', 'Documentacion tecnica del equipo actualizada.');
    }

    private function ensureTicketAssignedToTechnician(Ticket $ticket, User $user): void
    {
        abort_if($ticket->technician_id !== $user->id, 403);
    }

    private function ensureDiagnosticOwnedByTechnician(Diagnostic $diagnostic, User $user): void
    {
        abort_if($diagnostic->technician_id !== $user->id, 403);
    }

    private function ensureRepairOwnedByTechnician(Repair $repair, User $user): void
    {
        abort_if($repair->technician_id !== $user->id, 403);
    }

    private function resolveRecipient(Customer $customer, string $channel): ?string
    {
        return match ($channel) {
            'EMAIL' => $customer->email,
            'SMS', 'WHATSAPP' => $customer->mobile ?: $customer->phone,
            default => null,
        };
    }
}
