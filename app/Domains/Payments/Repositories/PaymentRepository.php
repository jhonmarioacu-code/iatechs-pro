<?php

declare(strict_types=1);

namespace App\Domains\Payments\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Payments\Models\Payment;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Payment::query()
            ->with([
                'invoice',
                'customer',
                'branch',
                'processedBy'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Payment {

        return Payment::find($id);
    }

    public function findOrFail(
        int $id
    ): Payment {

        return Payment::findOrFail($id);
    }

    public function create(
        array $data
    ): Payment {

        return Payment::create($data);
    }

    public function update(
        Payment $payment,
        array $data
    ): Payment {

        $payment->update($data);

        return $payment->refresh();
    }

    public function delete(
        Payment $payment
    ): bool {

        return (bool) $payment->delete();
    }

    public function existsPaymentNumber(
        string $number
    ): bool {

        return Payment::query()
            ->where(
                'payment_number',
                $number
            )
            ->exists();
    }

    public function totalPaidByInvoice(
        int $invoiceId
    ): float {

        return (float) Payment::query()
            ->where(
                'invoice_id',
                $invoiceId
            )
            ->where(
                'status',
                Payment::COMPLETED
            )
            ->sum('amount');
    }

    public function getCompletedPayments(): Collection
    {
        return Payment::query()
            ->where(
                'status',
                Payment::COMPLETED
            )
            ->get();
    }
}