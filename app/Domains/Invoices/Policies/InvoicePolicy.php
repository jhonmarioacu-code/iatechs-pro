<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Invoices\Models\Invoice;

class InvoicePolicy
{
    /**
     * Super Admin Bypass
     */
    protected function isSuperAdmin(
        User $user
    ): bool {

        return $user->hasRole(
            'super_admin'
        );
    }

    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'invoices.view'
        );
    }

    public function view(
        User $user,
        Invoice $invoice
    ): bool {

        if (
            $this->isSuperAdmin($user)
        ) {
            return true;
        }

        return
            $user->can('invoices.view')
            &&
            $user->company_id ===
            $invoice->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'invoices.create'
        );
    }

    public function update(
        User $user,
        Invoice $invoice
    ): bool {

        if (
            $this->isSuperAdmin($user)
        ) {
            return true;
        }

        return
            $user->can('invoices.update')
            &&
            $user->company_id ===
            $invoice->company_id;
    }

    public function delete(
        User $user,
        Invoice $invoice
    ): bool {

        if (
            $this->isSuperAdmin($user)
        ) {
            return true;
        }

        return
            $user->can('invoices.delete')
            &&
            $user->company_id ===
            $invoice->company_id;
    }

    public function restore(
        User $user,
        Invoice $invoice
    ): bool {

        return $user->can(
            'invoices.restore'
        );
    }

    public function forceDelete(
        User $user,
        Invoice $invoice
    ): bool {

        return $user->can(
            'invoices.force_delete'
        );
    }

    public function issue(
        User $user,
        Invoice $invoice
    ): bool {

        return $user->can(
            'invoices.issue'
        );
    }

    public function markAsPaid(
        User $user,
        Invoice $invoice
    ): bool {

        return $user->can(
            'invoices.mark_paid'
        );
    }

    public function markAsOverdue(
        User $user,
        Invoice $invoice
    ): bool {

        return $user->can(
            'invoices.mark_overdue'
        );
    }

    public function cancel(
        User $user,
        Invoice $invoice
    ): bool {

        return $user->can(
            'invoices.cancel'
        );
    }

    public function refund(
        User $user,
        Invoice $invoice
    ): bool {

        return $user->can(
            'invoices.refund'
        );
    }
}