<?php

declare(strict_types=1);

namespace App\Domains\Invoices\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Invoices\Models\Invoice;
use App\Domains\Invoices\Services\InvoiceService;

use App\Domains\Invoices\Requests\StoreInvoiceRequest;
use App\Domains\Invoices\Requests\UpdateInvoiceRequest;

use App\Domains\Invoices\Resources\InvoiceResource;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $service
    ) {}

    /**
     * List
     */
    public function index()
    {
        $this->authorize('viewAny', Invoice::class);

        return InvoiceResource::collection(
            $this->service->paginate()
        );
    }

    /**
     * Store
     */
    public function store(
        StoreInvoiceRequest $request
    )
    {
        $this->authorize(
            'create',
            Invoice::class
        );

        return (new InvoiceResource(
            $this->service->create(
                $request->validated()
            )
        ))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Show
     */
    public function show(
        Invoice $invoice
    )
    {
        $this->authorize(
            'view',
            $invoice
        );

        return new InvoiceResource(

            $invoice->load([

                'company',
                'branch',

                'customer',
                'billing',

                'ticket',
                'repair',

                'items',
                'payments'
            ])
        );
    }

    /**
     * Update
     */
    public function update(
        UpdateInvoiceRequest $request,
        Invoice $invoice
    )
    {
        $this->authorize(
            'update',
            $invoice
        );

        return new InvoiceResource(

            $this->service->update(
                $invoice,
                $request->validated()
            )
        );
    }

    /**
     * Delete
     */
    public function destroy(
        Invoice $invoice
    )
    {
        $this->authorize(
            'delete',
            $invoice
        );

        $this->service->delete(
            $invoice
        );

        return response()->json([

            'success' => true
        ]);
    }

    /**
     * Issue
     */
    public function issue(
        Invoice $invoice
    )
    {
        $this->authorize(
            'issue',
            $invoice
        );

        return new InvoiceResource(

            $this->service->issue(
                $invoice
            )
        );
    }

    /**
     * Mark Paid
     */
    public function markAsPaid(
        Invoice $invoice
    )
    {
        $this->authorize(
            'markAsPaid',
            $invoice
        );

        return new InvoiceResource(

            $this->service->markAsPaid(
                $invoice
            )
        );
    }

    /**
     * Mark Overdue
     */
    public function markAsOverdue(
        Invoice $invoice
    )
    {
        $this->authorize(
            'markAsOverdue',
            $invoice
        );

        return new InvoiceResource(

            $this->service->markAsOverdue(
                $invoice
            )
        );
    }

    /**
     * Cancel
     */
    public function cancel(
        Invoice $invoice
    )
    {
        $this->authorize(
            'cancel',
            $invoice
        );

        return new InvoiceResource(

            $this->service->cancel(
                $invoice
            )
        );
    }

    /**
     * Refund
     */
    public function refund(
        Invoice $invoice
    )
    {
        $this->authorize(
            'refund',
            $invoice
        );

        return new InvoiceResource(

            $this->service->refund(
                $invoice
            )
        );
    }
}
