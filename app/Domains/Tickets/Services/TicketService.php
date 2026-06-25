<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Domains\Tickets\Models\Ticket;
use App\Domains\Tickets\Repositories\TicketRepository;

class TicketService
{
    public function __construct(
        private TicketRepository $repository
    ) {}

    /**
     * Listado paginado
     */
    public function paginate(
        int $perPage = 20
    ) {
        return $this->repository
            ->paginate($perPage);
    }

    /**
     * Crear Ticket
     */
    public function createTicket(
        array $data
    ): Ticket {

        return DB::transaction(function () use ($data) {

            $data['uuid'] = (string) Str::uuid();

            $data['ticket_number'] =
                $this->generateTicketNumber();

            $data['status'] =
                $data['status'] ?? 'OPEN';

            $data['priority'] =
                $data['priority'] ?? 'MEDIUM';

            $data['received_at'] =
                now();

            $data['is_warranty'] =
                $data['is_warranty'] ?? false;

            return $this->repository
                ->create($data);
        });
    }

    /**
     * Actualizar Ticket
     */
    public function updateTicket(
        Ticket $ticket,
        array $data
    ): Ticket {

        return DB::transaction(function () use (
            $ticket,
            $data
        ) {

            unset(
                $data['uuid'],
                $data['ticket_number']
            );

            return $this->repository
                ->update(
                    $ticket,
                    $data
                );
        });
    }

    /**
     * Asignar técnico
     */
    public function assignTechnician(
        Ticket $ticket,
        int $technicianId
    ): Ticket {

        return $this->repository
            ->update(
                $ticket,
                [
                    'technician_id' => $technicianId,
                    'status'        => 'ASSIGNED',
                    'assigned_at'   => now(),
                ]
            );
    }

    /**
     * Cambiar estado
     */
    public function changeStatus(
        Ticket $ticket,
        string $status
    ): Ticket {

        return $this->repository
            ->update(
                $ticket,
                [
                    'status' => $status
                ]
            );
    }

    /**
     * Cerrar Ticket
     */
    public function closeTicket(
        Ticket $ticket
    ): Ticket {

        return $this->repository
            ->update(
                $ticket,
                [
                    'status'    => 'CLOSED',
                    'closed_at' => now(),
                ]
            );
    }

    /**
     * Cancelar Ticket
     */
    public function cancelTicket(
        Ticket $ticket
    ): Ticket {

        return $this->repository
            ->update(
                $ticket,
                [
                    'status'    => 'CANCELLED',
                    'closed_at' => now(),
                ]
            );
    }

    /**
     * Eliminar Ticket
     */
    public function deleteTicket(
        Ticket $ticket
    ): bool {

        return $this->repository
            ->delete($ticket);
    }

    /**
     * Buscar por UUID
     */
    public function findByUuid(
        string $uuid
    ): ?Ticket {

        return $this->repository
            ->findByUuid($uuid);
    }

    /**
     * Buscar por Ticket Number
     */
    public function findByTicketNumber(
        string $ticketNumber
    ): ?Ticket {

        return $this->repository
            ->findByTicketNumber(
                $ticketNumber
            );
    }

    /**
     * Generar número de ticket
     */
    private function generateTicketNumber(): string
    {
        do {

            $ticketNumber =
                'TK-' .
                date('Y') .
                '-' .
                strtoupper(
                    Str::random(8)
                );

        } while (
            $this->repository
                ->existsTicketNumber(
                    $ticketNumber
                )
        );

        return $ticketNumber;
    }
}