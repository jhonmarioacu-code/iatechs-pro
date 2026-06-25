<?php

declare(strict_types=1);

namespace App\Domains\Payments\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Payments\Models\Payment;
use App\Domains\Invoices\Models\Invoice;

use App\Domains\Payments\Repositories\PaymentRepository;

class PaymentService
{
    public function __construct(
        private PaymentRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Payment {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            $data['payment_number'] =
                $this->generateNumber();

            $data['status'] =
                Payment::PENDING;

            return $this->repository
                ->create($data);
        });
    }

    public function update(
        Payment $payment,
        array $data
    ): Payment {

        return $this->repository
            ->update(
                $payment,
                $data
            );
    }

    public function complete(
        Payment $payment
    ): Payment {

        return DB::transaction(function () use ($payment) {

            $payment =
                $this->repository->update(
                    $payment,
                    [
                        'status' => Payment::COMPLETED,
                        'paid_at' => now(),
                    ]
                );

            if ($payment->invoice instanceof Invoice) {
                $this->updateInvoiceStatus(
                    $payment->invoice
                );
            }

            return $payment;
        });
    }

    public function cancel(
        Payment $payment
    ): Payment {

        return DB::transaction(function () use ($payment) {

            $payment =
                $this->repository->update(
                    $payment,
                    [
                        'status' => Payment::CANCELLED,
                    ]
                );

            if ($payment->invoice instanceof Invoice) {
                $this->updateInvoiceStatus(
                    $payment->invoice
                );
            }

            return $payment;
        });
    }

    public function refund(
        Payment $payment
    ): Payment {

        return DB::transaction(function () use ($payment) {

            $payment =
                $this->repository->update(
                    $payment,
                    [
                        'status' => Payment::REFUNDED,
                        'paid_at' => null,
                    ]
                );

            if ($payment->invoice instanceof Invoice) {
                $this->updateInvoiceStatus(
                    $payment->invoice
                );
            }

            return $payment;
        });
    }

    private function updateInvoiceStatus(
        Invoice $invoice
    ): void {

        $paidAmount =
            $this->repository
                ->totalPaidByInvoice(
                    $invoice->id
                );

        if ($paidAmount >= $invoice->total) {

            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return;
        }

        if ($paidAmount > 0) {

            $invoice->update([
                'status' => 'partially_paid',
                'paid_at' => null,
            ]);

            return;
        }

        $invoice->update([
            'status' => 'issued',
            'paid_at' => null,
        ]);
    }

    private function generateNumber(): string
    {
        do {

            $number =
                'PAY-' .
                date('Y') .
                '-' .
                strtoupper(
                    Str::random(8)
                );

        } while (
            $this->repository
                ->existsPaymentNumber(
                    $number
                )
        );

        return $number;
    }
}
