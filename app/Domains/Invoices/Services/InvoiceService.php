<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Invoices\Models\Invoice;
use App\Domains\Invoices\Repositories\InvoiceRepository;

class InvoiceService
{
    public function __construct(
        protected InvoiceRepository $repository
    ) {}

    /**
     * Paginate
     */
    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    /**
     * Find
     */
    public function find(
        int $id
    ): ?Invoice {

        return $this->repository
            ->find($id);
    }

    /**
     * Create
     */
    public function create(
        array $data
    ): Invoice {

        return DB::transaction(
            function () use ($data) {

                $data['uuid'] =
                    (string) Str::uuid();

                $data['invoice_number'] =
                    $this->generateNumber();

                $data['status'] =
                    'draft';

                $data['subtotal'] =
                    $data['subtotal'] ?? 0;

                $data['tax'] =
                    $data['tax'] ?? 0;

                $data['discount'] =
                    $data['discount'] ?? 0;

                $data['total'] =
                    (
                        $data['subtotal']
                        +
                        $data['tax']
                    )
                    -
                    $data['discount'];

                return $this->repository
                    ->create($data);
            }
        );
    }

    /**
     * Update
     */
    public function update(
        Invoice $invoice,
        array $data
    ): Invoice {

        if (
            isset($data['subtotal']) ||
            isset($data['tax']) ||
            isset($data['discount'])
        ) {

            $subtotal =
                $data['subtotal']
                ??
                $invoice->subtotal;

            $tax =
                $data['tax']
                ??
                $invoice->tax;

            $discount =
                $data['discount']
                ??
                $invoice->discount;

            $data['total'] =
                (
                    $subtotal
                    +
                    $tax
                )
                -
                $discount;
        }

        return $this->repository
            ->update(
                $invoice,
                $data
            );
    }

    /**
     * Delete
     */
    public function delete(
        Invoice $invoice
    ): bool {

        return $this->repository
            ->delete($invoice);
    }

    /**
     * Issue
     */
    public function issue(
        Invoice $invoice
    ): Invoice {

        return $this->repository
            ->update(
                $invoice,
                [
                    'status' => 'issued',
                    'issued_at' => now(),
                ]
            );
    }

    /**
     * Mark Paid
     */
    public function markAsPaid(
        Invoice $invoice
    ): Invoice {

        return $this->repository
            ->update(
                $invoice,
                [
                    'status' => 'paid',
                    'paid_at' => now(),
                ]
            );
    }

    /**
     * Mark Overdue
     */
    public function markAsOverdue(
        Invoice $invoice
    ): Invoice {

        return $this->repository
            ->update(
                $invoice,
                [
                    'status' => 'overdue',
                ]
            );
    }

    /**
     * Cancel
     */
    public function cancel(
        Invoice $invoice
    ): Invoice {

        return $this->repository
            ->update(
                $invoice,
                [
                    'status' => 'cancelled',
                ]
            );
    }

    /**
     * Refund
     */
    public function refund(
        Invoice $invoice
    ): Invoice {

        return $this->repository
            ->update(
                $invoice,
                [
                    'status' => 'refunded',
                ]
            );
    }

    /**
     * Generate Invoice Number
     */
    private function generateNumber(): string
    {
        do {

            $number =
                'INV-'
                .
                date('Y')
                .
                '-'
                .
                strtoupper(
                    Str::random(8)
                );

        } while (

            $this->repository
                ->existsInvoiceNumber(
                    $number
                )

        );

        return $number;
    }
}