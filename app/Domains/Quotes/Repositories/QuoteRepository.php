<?php

declare(strict_types=1);

namespace App\Domains\Quotes\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Quotes\Models\Quote;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuoteRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Quote::query()
            ->with([
                'ticket',
                'diagnostic',
                'items'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Quote {

        return Quote::find($id);
    }

    public function create(
        array $data
    ): Quote {

        return Quote::create($data);
    }

    public function update(
        Quote $quote,
        array $data
    ): Quote {

        $quote->update($data);

        return $quote->refresh();
    }

    public function delete(
        Quote $quote
    ): bool {

        return (bool) $quote->delete();
    }

    public function existsQuoteNumber(
        string $number
    ): bool {

        return Quote::query()
            ->where(
                'quote_number',
                $number
            )
            ->exists();
    }
}