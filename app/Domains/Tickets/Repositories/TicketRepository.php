<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Tickets\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository
{
    use ProvidesRepositoryDefaults;

    /**
     * Listado paginado
     */
    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Ticket::query()
            ->with([
                'company',
                'branch',
                'customer',
                'device',
                'technician'
            ])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Obtener todos
     */
    public function all(): Collection
    {
        return Ticket::query()
            ->with([
                'company',
                'branch',
                'customer',
                'device',
                'technician'
            ])
            ->latest()
            ->get();
    }

    /**
     * Buscar por ID
     */
    public function find(
        int $id
    ): ?Ticket {

        return Ticket::query()
            ->with([
                'company',
                'branch',
                'customer',
                'device',
                'technician'
            ])
            ->find($id);
    }

    /**
     * Buscar por UUID
     */
    public function findByUuid(
        string $uuid
    ): ?Ticket {

        return Ticket::query()
            ->where('uuid', $uuid)
            ->first();
    }

    /**
     * Buscar por número de ticket
     */
    public function findByTicketNumber(
        string $ticketNumber
    ): ?Ticket {

        return Ticket::query()
            ->where(
                'ticket_number',
                $ticketNumber
            )
            ->first();
    }

    /**
     * Tickets por empresa
     */
    public function getByCompany(
        int $companyId
    ): Collection {

        return Ticket::query()
            ->where('company_id', $companyId)
            ->latest()
            ->get();
    }

    /**
     * Tickets por sucursal
     */
    public function getByBranch(
        int $branchId
    ): Collection {

        return Ticket::query()
            ->where('branch_id', $branchId)
            ->latest()
            ->get();
    }

    /**
     * Tickets por cliente
     */
    public function getByCustomer(
        int $customerId
    ): Collection {

        return Ticket::query()
            ->where('customer_id', $customerId)
            ->latest()
            ->get();
    }

    /**
     * Tickets por técnico
     */
    public function getByTechnician(
        int $technicianId
    ): Collection {

        return Ticket::query()
            ->where(
                'technician_id',
                $technicianId
            )
            ->latest()
            ->get();
    }

    /**
     * Tickets abiertos
     */
    public function getOpenTickets(): Collection
    {
        return Ticket::query()
            ->open()
            ->latest()
            ->get();
    }

    /**
     * Tickets cerrados
     */
    public function getClosedTickets(): Collection
    {
        return Ticket::query()
            ->closed()
            ->latest()
            ->get();
    }

    /**
     * Crear ticket
     */
    public function create(
        array $data
    ): Ticket {

        return Ticket::create($data);
    }

    /**
     * Actualizar ticket
     */
    public function update(
        Ticket $ticket,
        array $data
    ): Ticket {

        $ticket->update($data);

        return $ticket->refresh();
    }

    /**
     * Eliminar ticket
     */
    public function delete(
        Ticket $ticket
    ): bool {

        return (bool) $ticket->delete();
    }

    /**
     * Verificar número de ticket
     */
    public function existsTicketNumber(
        string $ticketNumber
    ): bool {

        return Ticket::query()
            ->where(
                'ticket_number',
                $ticketNumber
            )
            ->exists();
    }

    /**
     * Contar tickets por empresa
     */
    public function countByCompany(
        int $companyId
    ): int {

        return Ticket::query()
            ->where('company_id', $companyId)
            ->count();
    }

    /**
     * Contar tickets abiertos
     */
    public function countOpen(): int
    {
        return Ticket::query()
            ->open()
            ->count();
    }

    /**
     * Contar tickets cerrados
     */
    public function countClosed(): int
    {
        return Ticket::query()
            ->closed()
            ->count();
    }
}