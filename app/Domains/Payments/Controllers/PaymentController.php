<?php

declare(strict_types=1);

namespace App\Domains\Payments\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Payments\Models\Payment;

use App\Domains\Payments\Services\PaymentService;

use App\Domains\Payments\Requests\StorePaymentRequest;
use App\Domains\Payments\Requests\UpdatePaymentRequest;

use App\Domains\Payments\Resources\PaymentResource;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $service
    ) {
        $this->authorizeResource(
            Payment::class,
            'payment'
        );
    }

    public function index()
    {
        return PaymentResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StorePaymentRequest $request
    )
    {
        $this->authorize(
            'create',
            Payment::class
        );

        return (new PaymentResource(
            $this->service->create(
                $request->validated()
            )
        ))
            ->response()
            ->setStatusCode(200);
    }

    public function show(
        Payment $payment
    )
    {
        return new PaymentResource(
            $payment->load([
                'company',
                'branch',
                'invoice',
                'customer',
                'processedBy'
            ])
        );
    }

    public function update(
        UpdatePaymentRequest $request,
        Payment $payment
    )
    {
        return new PaymentResource(
            $this->service->update(
                $payment,
                $request->validated()
            )
        );
    }

    public function destroy(
        Payment $payment
    )
    {
        $this->authorize(
            'delete',
            $payment
        );

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully.'
        ]);
    }

    public function complete(
        Payment $payment
    )
    {
        $this->authorize(
            'complete',
            $payment
        );

        return new PaymentResource(
            $this->service->complete(
                $payment
            )
        );
    }

    public function cancel(
        Payment $payment
    )
    {
        $this->authorize(
            'cancel',
            $payment
        );

        return new PaymentResource(
            $this->service->cancel(
                $payment
            )
        );
    }

    public function refund(
        Payment $payment
    )
    {
        $this->authorize(
            'refund',
            $payment
        );

        return new PaymentResource(
            $this->service->refund(
                $payment
            )
        );
    }
}
