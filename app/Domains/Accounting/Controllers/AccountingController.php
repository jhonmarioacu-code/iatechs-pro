<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Accounting\Models\Account;
use App\Domains\Accounting\Models\JournalEntry;

use App\Domains\Accounting\Services\AccountingService;

use App\Domains\Accounting\Requests\StoreAccountRequest;
use App\Domains\Accounting\Requests\UpdateAccountRequest;
use App\Domains\Accounting\Requests\StoreJournalEntryRequest;

class AccountingController extends Controller
{
    public function __construct(
        protected AccountingService $service
    ) {}

    public function accounts()
    {
        $this->authorize('viewAny', Account::class);

        return $this->service
            ->paginateAccounts();
    }

    public function storeAccount(
        StoreAccountRequest $request
    ) {
        $this->authorize('create', Account::class);

        return $this->service
            ->createAccount(
                $request->validated()
            );
    }

    public function updateAccount(
        UpdateAccountRequest $request,
        Account $account
    ) {
        $this->authorize('update', $account);

        return $this->service
            ->updateAccount(
                $account,
                $request->validated()
            );
    }

    public function journalEntries()
    {
        $this->authorize('viewAny', JournalEntry::class);

        return $this->service
            ->paginateJournalEntries();
    }

    public function storeJournalEntry(
        StoreJournalEntryRequest $request
    ) {
        $this->authorize('create', JournalEntry::class);

        return $this->service
            ->createJournalEntry(
                $request->validated()
            );
    }

    public function post(
        JournalEntry $journalEntry
    ) {
        $this->authorize('post', $journalEntry);

        return $this->service
            ->postEntry(
                $journalEntry
            );
    }

    public function cancel(
        JournalEntry $journalEntry
    ) {
        $this->authorize('cancel', $journalEntry);

        return $this->service
            ->cancelEntry(
                $journalEntry
            );
    }
}
