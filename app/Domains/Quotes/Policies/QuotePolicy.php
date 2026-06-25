<?php

declare(strict_types=1);

namespace App\Domains\Quotes\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Quotes\Models\Quote;

class QuotePolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'quotes.view'
        );
    }

    public function view(
        User $user,
        Quote $quote
    ): bool {

        return
            $user->can('quotes.view')
            &&
            $user->company_id ===
            $quote->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'quotes.create'
        );
    }

    public function update(
        User $user,
        Quote $quote
    ): bool {

        return
            $user->can('quotes.update')
            &&
            $user->company_id ===
            $quote->company_id;
    }

    public function delete(
        User $user,
        Quote $quote
    ): bool {

        return
            $user->can('quotes.delete')
            &&
            $user->company_id ===
            $quote->company_id;
    }

    public function approve(
        User $user,
        Quote $quote
    ): bool {

        return
            $user->can('quotes.approve')
            &&
            $user->company_id ===
            $quote->company_id;
    }

    public function reject(
        User $user,
        Quote $quote
    ): bool {

        return
            $user->can('quotes.reject')
            &&
            $user->company_id ===
            $quote->company_id;
    }

    public function cancel(
        User $user,
        Quote $quote
    ): bool {

        return
            $user->can('quotes.cancel')
            &&
            $user->company_id ===
            $quote->company_id;
    }
}