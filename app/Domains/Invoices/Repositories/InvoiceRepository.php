<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Invoices\Models\Invoice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InvoiceRepository
{
    use ProvidesRepositoryDefaults;

    /**
     * Paginate
     */
    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Invoice::query()

            ->with([
                'company',
                'branch',
                'customer',

                'billing',

                'ticket',
                'repair',

                'items',
                'payments'
            ])

            ->latest()

            ->paginate($perPage);
    }

    /**
     * Find
     */
    public function find(
        int $id
    ): ?Invoice {

        return Invoice::query()

            ->with([
                'company',
                'branch',
                'customer',

                'billing',

                'ticket',
                'repair',

                'items',
                'payments'
            ])

            ->find($id);
    }

    /**
     * Create
     */
    public function create(
        array $data
    ): Invoice {

        return Invoice::create(
            $data
        );
    }

    /**
     * Update
     */
    public function update(
        Invoice $invoice,
        array $data
    ): Invoice {

        $invoice->update(
            $data
        );

        return $invoice->refresh();
    }

    /**
     * Delete
     */
    public function delete(
        Invoice $invoice
    ): bool {

        return (bool) $invoice->delete();
    }

    /**
     * Invoice Number Exists
     */
    public function existsInvoiceNumber(
        string $number
    ): bool {

        return Invoice::query()

            ->where(
                'invoice_number',
                $number
            )

            ->exists();
    }

    /**
     * Find By UUID
     */
    public function findByUuid(
        string $uuid
    ): ?Invoice {

        return Invoice::query()

            ->where(
                'uuid',
                $uuid
            )

            ->first();
    }
}