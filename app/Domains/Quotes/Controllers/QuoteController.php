<?php

declare(strict_types=1);

namespace App\Domains\Quotes\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Quotes\Models\Quote;
use App\Domains\Quotes\Services\QuoteService;
use App\Domains\Quotes\Resources\QuoteResource;
use App\Domains\Quotes\Requests\StoreQuoteRequest;
use App\Domains\Quotes\Requests\UpdateQuoteRequest;

class QuoteController extends Controller
{
    public function __construct(
        private QuoteService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Quote::class);

        return QuoteResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreQuoteRequest $request
    )
    {
        $this->authorize('create', Quote::class);

        return new QuoteResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        Quote $quote
    )
    {
        $this->authorize('view', $quote);

        return new QuoteResource(
            $quote->load([
                'ticket',
                'diagnostic',
                'items'
            ])
        );
    }

    public function update(
        UpdateQuoteRequest $request,
        Quote $quote
    )
    {
        $this->authorize('update', $quote);

        return new QuoteResource(
            $this->service->update(
                $quote,
                $request->validated()
            )
        );
    }

    public function destroy(
        Quote $quote
    )
    {
        $this->authorize('delete', $quote);

        $this->service->delete(
            $quote
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function approve(
        Quote $quote
    )
    {
        $this->authorize('approve', $quote);

        return new QuoteResource(
            $this->service->approve(
                $quote
            )
        );
    }

    public function reject(
        Quote $quote
    )
    {
        $this->authorize('reject', $quote);

        return new QuoteResource(
            $this->service->reject(
                $quote
            )
        );
    }

    public function cancel(
        Quote $quote
    )
    {
        $this->authorize('cancel', $quote);

        return new QuoteResource(
            $this->service->cancel(
                $quote
            )
        );
    }
}
