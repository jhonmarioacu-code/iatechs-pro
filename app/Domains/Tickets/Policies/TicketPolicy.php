<?php

declare(strict_types=1);

namespace App\Domains\Tickets\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Tickets\Models\Ticket;

class TicketPolicy
{
    /**
     * Ver listado
     */
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'tickets.view'
        );
    }

    /**
     * Ver ticket
     */
    public function view(
        User $user,
        Ticket $ticket
    ): bool {

        return
            $user->can('tickets.view')
            &&
            $user->company_id ===
            $ticket->company_id;
    }

    /**
     * Crear ticket
     */
    public function create(
        User $user
    ): bool {

        return $user->can(
            'tickets.create'
        );
    }

    /**
     * Actualizar ticket
     */
    public function update(
        User $user,
        Ticket $ticket
    ): bool {

        return
            $user->can('tickets.update')
            &&
            $user->company_id ===
            $ticket->company_id;
    }

    /**
     * Eliminar ticket
     */
    public function delete(
        User $user,
        Ticket $ticket
    ): bool {

        return
            $user->can('tickets.delete')
            &&
            $user->company_id ===
            $ticket->company_id;
    }

    /**
     * Asignar técnico
     */
    public function assign(
        User $user,
        Ticket $ticket
    ): bool {

        return
            $user->can('tickets.assign')
            &&
            $user->company_id ===
            $ticket->company_id;
    }

    /**
     * Cerrar ticket
     */
    public function close(
        User $user,
        Ticket $ticket
    ): bool {

        return
            $user->can('tickets.close')
            &&
            $user->company_id ===
            $ticket->company_id;
    }

    /**
     * Cancelar ticket
     */
    public function cancel(
        User $user,
        Ticket $ticket
    ): bool {

        return
            $user->can('tickets.cancel')
            &&
            $user->company_id ===
            $ticket->company_id;
    }

    /**
     * Restaurar
     */
    public function restore(
        User $user,
        Ticket $ticket
    ): bool {

        return false;
    }

    /**
     * Eliminación permanente
     */
    public function forceDelete(
        User $user,
        Ticket $ticket
    ): bool {

        return false;
    }
}