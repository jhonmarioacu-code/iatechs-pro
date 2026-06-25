<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Invoices\Models\InvoiceItem;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class InvoiceItemRepository
{
    use ProvidesRepositoryDefaults;

    /**
     * Paginate items.
     */
    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return InvoiceItem::query()
            ->with([
                'invoice',
                'product'
            ])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find item by id.
     */
    public function find(
        int $id
    ): ?InvoiceItem {

        return InvoiceItem::query()
            ->with([
                'invoice',
                'product'
            ])
            ->find($id);
    }

    /**
     * Get all items from invoice.
     */
    public function byInvoice(
        Invoice $invoice
    ): Collection {

        return InvoiceItem::query()
            ->where(
                'invoice_id',
                $invoice->id
            )
            ->orderBy(
                'sort_order'
            )
            ->get();
    }

    /**
     * Create item.
     */
    public function create(
        array $data
    ): InvoiceItem {

        return InvoiceItem::create(
            $data
        );
    }

    /**
     * Update item.
     */
    public function update(
        InvoiceItem $item,
        array $data
    ): InvoiceItem {

        $item->update(
            $data
        );

        return $item->refresh();
    }

    /**
     * Delete item.
     */
    public function delete(
        InvoiceItem $item
    ): bool {

        return (bool)
            $item->delete();
    }

    /**
     * Count invoice items.
     */
    public function countByInvoice(
        Invoice $invoice
    ): int {

        return InvoiceItem::query()
            ->where(
                'invoice_id',
                $invoice->id
            )
            ->count();
    }

    /**
     * Sum invoice items.
     */
    public function totalByInvoice(
        Invoice $invoice
    ): float {

        return (float)
            InvoiceItem::query()
                ->where(
                    'invoice_id',
                    $invoice->id
                )
                ->sum('total');
    }
}