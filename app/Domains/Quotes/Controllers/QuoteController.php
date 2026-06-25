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
        return QuoteResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreQuoteRequest $request
    )
    {
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
        return new QuoteResource(
            $this->service->cancel(
                $quote
            )
        );
    }
}