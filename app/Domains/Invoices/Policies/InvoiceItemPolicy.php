<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Invoices\Models\InvoiceItem;

class InvoiceItemPolicy
{
    /**
     * View Any
     */
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'invoices.view'
        );
    }

    /**
     * View
     */
    public function view(
        User $user,
        InvoiceItem $item
    ): bool {

        return
            $user->can('invoices.view')
            &&
            $user->company_id ===
            $item->company_id;
    }

    /**
     * Create
     */
    public function create(
        User $user
    ): bool {

        return $user->can(
            'invoices.create'
        );
    }

    /**
     * Update
     */
    public function update(
        User $user,
        InvoiceItem $item
    ): bool {

        return
            $user->can('invoices.update')
            &&
            $user->company_id ===
            $item->company_id;
    }

    /**
     * Delete
     */
    public function delete(
        User $user,
        InvoiceItem $item
    ): bool {

        return
            $user->can('invoices.delete')
            &&
            $user->company_id ===
            $item->company_id;
    }

    /**
     * Restore
     */
    public function restore(
        User $user,
        InvoiceItem $item
    ): bool {

        return
            $user->can('invoices.update')
            &&
            $user->company_id ===
            $item->company_id;
    }

    /**
     * Force Delete
     */
    public function forceDelete(
        User $user,
        InvoiceItem $item
    ): bool {

        return
            $user->can('invoices.delete')
            &&
            $user->company_id ===
            $item->company_id;
    }
}