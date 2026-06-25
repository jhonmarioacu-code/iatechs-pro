<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Accounting\Models\Account;
use App\Domains\Accounting\Models\JournalEntry;

use App\Domains\Accounting\Repositories\AccountRepository;
use App\Domains\Accounting\Repositories\JournalEntryRepository;

class AccountingService
{
    public function __construct(

        protected AccountRepository $accounts,

        protected JournalEntryRepository $entries

    ) {}

    public function paginateAccounts(
        int $perPage = 25
    ) {
        return $this->accounts
            ->query()
            ->orderBy('code')
            ->paginate($perPage);
    }

    public function paginateJournalEntries(
        int $perPage = 25
    ) {
        return $this->entries
            ->query()
            ->latest()
            ->paginate($perPage);
    }

    public function createAccount(
        array $data
    ): Account {

        $data['uuid'] = Str::uuid();

        return $this->accounts->create(
            $data
        );
    }

    public function updateAccount(
        Account $account,
        array $data
    ): Account {

        return $this->accounts->update(
            $account,
            $data
        );
    }

    public function createJournalEntry(
        array $data
    ): JournalEntry {

        return DB::transaction(
            function () use ($data) {

                $entry = $this->entries->create([

                    'uuid' => Str::uuid(),

                    'company_id' =>
                        $data['company_id'],

                    'entry_number' =>
                        $data['entry_number'],

                    'entry_date' =>
                        $data['entry_date'],

                    'description' =>
                        $data['description'] ?? null,

                    'status' => 'draft',

                    'created_by' =>
                        $data['created_by'] ?? null,
                ]);

                foreach (
                    $data['lines']
                    as $line
                ) {

                    $entry->lines()->create([

                        'account_id' =>
                            $line['account_id'],

                        'debit' =>
                            $line['debit'] ?? 0,

                        'credit' =>
                            $line['credit'] ?? 0,

                        'description' =>
                            $line['description'] ?? null,
                    ]);
                }

                return $entry->load(
                    'lines'
                );
            }
        );
    }

    public function validateEntry(
        JournalEntry $entry
    ): bool {

        $debit = $entry->lines()
            ->sum('debit');

        $credit = $entry->lines()
            ->sum('credit');

        return round(
            $debit,
            2
        ) === round(
            $credit,
            2
        );
    }

    public function postEntry(
        JournalEntry $entry
    ): JournalEntry {

        if (
            !$this->validateEntry(
                $entry
            )
        ) {

            throw new \Exception(
                'El asiento no está balanceado.'
            );
        }

        return $this->entries->update(
            $entry,
            [
                'status' => 'posted'
            ]
        );
    }

    public function cancelEntry(
        JournalEntry $entry
    ): JournalEntry {

        return $this->entries->update(
            $entry,
            [
                'status' => 'cancelled'
            ]
        );
    }
}
