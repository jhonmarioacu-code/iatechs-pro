<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Invoices\Models\InvoiceItem;

use App\Domains\Invoices\Services\InvoiceItemService;

use App\Domains\Invoices\Requests\StoreInvoiceItemRequest;
use App\Domains\Invoices\Requests\UpdateInvoiceItemRequest;

use App\Domains\Invoices\Resources\InvoiceItemResource;

class InvoiceItemController extends Controller
{
    public function __construct(
        private InvoiceItemService $service
    ) {}

    /**
     * List
     */
    public function index()
    {
        $this->authorize('viewAny', InvoiceItem::class);

        return InvoiceItemResource::collection(
            $this->service
                ->paginate()
        );
    }

    /**
     * Store
     */
    public function store(
        StoreInvoiceItemRequest $request
    )
    {
        $this->authorize('create', InvoiceItem::class);

        return new InvoiceItemResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    /**
     * Show
     */
    public function show(
        InvoiceItem $invoiceItem
    )
    {
        $this->authorize('view', $invoiceItem);

        return new InvoiceItemResource(
            $invoiceItem->load([
                'invoice',
                'product'
            ])
        );
    }

    /**
     * Update
     */
    public function update(
        UpdateInvoiceItemRequest $request,
        InvoiceItem $invoiceItem
    )
    {
        $this->authorize('update', $invoiceItem);

        return new InvoiceItemResource(
            $this->service->update(
                $invoiceItem,
                $request->validated()
            )
        );
    }

    /**
     * Delete
     */
    public function destroy(
        InvoiceItem $invoiceItem
    )
    {
        $this->authorize('delete', $invoiceItem);

        $this->service->delete(
            $invoiceItem
        );

        return response()->json([
            'success' => true,
            'message' => 'Invoice item deleted successfully.'
        ]);
    }
}
