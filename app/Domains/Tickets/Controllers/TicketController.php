<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Domains\Tickets\Models\Ticket;
use App\Domains\Tickets\Services\TicketService;
use App\Domains\Tickets\Requests\StoreTicketRequest;
use App\Domains\Tickets\Requests\UpdateTicketRequest;
use App\Domains\Tickets\Resources\TicketResource;

class TicketController extends Controller
{
    public function __construct(
        private TicketService $service
    ) {}

    /**
     * Listado de tickets
     */
    public function index()
    {
        $this->authorize('viewAny', Ticket::class);

        $tickets = $this->service->paginate();

        return TicketResource::collection(
            $tickets
        );
    }

    /**
     * Crear ticket
     */
    public function store(
        StoreTicketRequest $request
    ) {
        $this->authorize('create', Ticket::class);

        $ticket = $this->service
            ->createTicket(
                $request->validated()
            );

        return (new TicketResource($ticket))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Ver ticket
     */
    public function show(
        Ticket $ticket
    ): TicketResource {
        $this->authorize('view', $ticket);

        return new TicketResource(
            $ticket->load([
                'company',
                'branch',
                'customer',
                'device',
                'technician'
            ])
        );
    }

    /**
     * Actualizar ticket
     */
    public function update(
        UpdateTicketRequest $request,
        Ticket $ticket
    ): TicketResource {
        $this->authorize('update', $ticket);

        $ticket = $this->service
            ->updateTicket(
                $ticket,
                $request->validated()
            );

        return new TicketResource(
            $ticket
        );
    }

    /**
     * Eliminar ticket
     */
    public function destroy(
        Ticket $ticket
    ): JsonResponse {
        $this->authorize('delete', $ticket);

        $this->service
            ->deleteTicket(
                $ticket
            );

        return response()->json([
            'success' => true,
            'message' => 'Ticket eliminado correctamente.'
        ]);
    }

    /**
     * Asignar técnico
     */
    public function assign(
        Request $request,
        Ticket $ticket
    ): TicketResource {
        $this->authorize('assign', $ticket);

        $request->validate([
            'technician_id' => [
                'required',
                'exists:users,id'
            ]
        ]);

        $ticket = $this->service
            ->assignTechnician(
                $ticket,
                $request->technician_id
            );

        return new TicketResource(
            $ticket
        );
    }

    /**
     * Cerrar ticket
     */
    public function close(
        Ticket $ticket
    ): TicketResource {
        $this->authorize('close', $ticket);

        $ticket = $this->service
            ->closeTicket(
                $ticket
            );

        return new TicketResource(
            $ticket
        );
    }

    /**
     * Cancelar ticket
     */
    public function cancel(
        Ticket $ticket
    ): TicketResource {
        $this->authorize('cancel', $ticket);

        $ticket = $this->service
            ->cancelTicket(
                $ticket
            );

        return new TicketResource(
            $ticket
        );
    }

    /**
     * Cambiar estado
     */
    public function changeStatus(
        Request $request,
        Ticket $ticket
    ): TicketResource {
        $this->authorize('update', $ticket);

        $request->validate([
            'status' => [
                'required',
                'string'
            ]
        ]);

        $ticket = $this->service
            ->changeStatus(
                $ticket,
                $request->status
            );

        return new TicketResource(
            $ticket
        );
    }
}
