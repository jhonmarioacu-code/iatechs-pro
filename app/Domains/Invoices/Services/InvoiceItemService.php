<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Services;

use Illuminate\Support\Facades\DB;

use App\Domains\Invoices\Models\Invoice;
use App\Domains\Invoices\Models\InvoiceItem;

use App\Domains\Invoices\Repositories\InvoiceRepository;
use App\Domains\Invoices\Repositories\InvoiceItemRepository;

class InvoiceItemService
{
    public function __construct(
        private InvoiceItemRepository $repository,
        private InvoiceRepository $invoiceRepository
    ) {}

    /**
     * Create Invoice Item
     */public function paginate(
    int $perPage = 20
    )
    {
    return $this->repository
        ->paginate($perPage);
    }
    public function create(
        array $data
    ): InvoiceItem {

        return DB::transaction(function () use ($data) {

            $data['total'] = $this->calculateItemTotal(
                quantity: $data['quantity'] ?? 1,
                unitPrice: $data['unit_price'] ?? 0,
                tax: $data['tax'] ?? 0,
                discount: $data['discount'] ?? 0
            );

            $item = $this->repository
                ->create($data);

            if ($item->invoice instanceof Invoice) {
                $this->recalculateInvoice(
                    $item->invoice
                );
            }

            return $item;
        });
    }

    /**
     * Update Invoice Item
     */
    public function update(
        InvoiceItem $item,
        array $data
    ): InvoiceItem {

        return DB::transaction(function () use (
            $item,
            $data
        ) {

            $quantity =
                $data['quantity']
                ?? $item->quantity;

            $unitPrice =
                $data['unit_price']
                ?? $item->unit_price;

            $tax =
                $data['tax']
                ?? $item->tax;

            $discount =
                $data['discount']
                ?? $item->discount;

            $data['total'] =
                $this->calculateItemTotal(
                    $quantity,
                    $unitPrice,
                    $tax,
                    $discount
                );

            $item = $this->repository
                ->update(
                    $item,
                    $data
                );

            if ($item->invoice instanceof Invoice) {
                $this->recalculateInvoice(
                    $item->invoice
                );
            }

            return $item;
        });
    }

    /**
     * Delete Invoice Item
     */
    public function delete(
        InvoiceItem $item
    ): bool {

        return DB::transaction(function () use (
            $item
        ) {

            $invoice = $item->invoice;

            $deleted = $this->repository
                ->delete($item);

            if ($invoice instanceof Invoice) {
                $this->recalculateInvoice(
                    $invoice
                );
            }

            return $deleted;
        });
    }

    /**
     * Recalculate Invoice Totals
     */
    public function recalculateInvoice(
        Invoice $invoice
    ): void {

        $items = $this->repository
            ->byInvoice(
                $invoice
            );

        $subtotal = 0.0;
        foreach ($items->toArray() as $item) {
            $subtotal +=
                ((float) ($item['quantity'] ?? 0))
                * ((float) ($item['unit_price'] ?? 0));
        }

        $tax =
            $items->sum('tax');

        $discount =
            $items->sum('discount');

        $total =
            ($subtotal + $tax)
            - $discount;

        $this->invoiceRepository
            ->update(
                $invoice,
                [

                    'subtotal' => $subtotal,

                    'tax' => $tax,

                    'discount' => $discount,

                    'total' => $total,
                ]
            );
    }

    /**
     * Calculate Item Total
     */
    private function calculateItemTotal(
        float $quantity,
        float $unitPrice,
        float $tax,
        float $discount
    ): float {

        return (
            ($quantity * $unitPrice)
            + $tax
        ) - $discount;
    }
}
