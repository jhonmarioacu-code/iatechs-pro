<?php

declare(strict_types=1);

namespace App\Domains\Quotes\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Quotes\Models\Quote;
use App\Domains\Quotes\Repositories\QuoteRepository;

class QuoteService
{
    public function __construct(
        private QuoteRepository $repository
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
    ): Quote {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            $data['quote_number'] =
                $this->generateNumber();

            $data['status'] =
                'DRAFT';

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
        });
    }

    public function update(
        Quote $quote,
        array $data
    ): Quote {

        if (
            isset($data['subtotal']) ||
            isset($data['tax']) ||
            isset($data['discount'])
        ) {

            $subtotal =
                $data['subtotal']
                ??
                $quote->subtotal;

            $tax =
                $data['tax']
                ??
                $quote->tax;

            $discount =
                $data['discount']
                ??
                $quote->discount;

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
                $quote,
                $data
            );
    }

    public function approve(
        Quote $quote
    ): Quote {

        return $this->repository
            ->update(
                $quote,
                [
                    'status' => 'APPROVED',
                    'approved_at' => now(),
                ]
            );
    }

    public function sendForApproval(
        Quote $quote
    ): Quote {

        return $this->repository
            ->update(
                $quote,
                [
                    'status' => 'PENDING_APPROVAL',
                ]
            );
    }

    public function reject(
        Quote $quote
    ): Quote {

        return $this->repository
            ->update(
                $quote,
                [
                    'status' => 'REJECTED',
                    'rejected_at' => now(),
                ]
            );
    }

    public function cancel(
        Quote $quote
    ): Quote {

        return $this->repository
            ->update(
                $quote,
                [
                    'status' => 'CANCELLED'
                ]
            );
    }

    public function delete(
        Quote $quote
    ): bool {

        return $this->repository
            ->delete($quote);
    }

    private function generateNumber(): string
    {
        do {

            $number =
                'QT-' .
                date('Y') .
                '-' .
                strtoupper(
                    Str::random(8)
                );

        } while (
            $this->repository
                ->existsQuoteNumber(
                    $number
                )
        );

        return $number;
    }
}
