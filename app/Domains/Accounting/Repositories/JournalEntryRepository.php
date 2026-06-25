<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Accounting\Models\JournalEntry;

class JournalEntryRepository
{
    use ProvidesRepositoryDefaults;

    public function query()
    {
        return JournalEntry::query();
    }

    public function find(
        int $id
    ): ?JournalEntry {

        return JournalEntry::find($id);
    }

    public function create(
        array $data
    ): JournalEntry {

        return JournalEntry::create(
            $data
        );
    }

    public function update(
        JournalEntry $entry,
        array $data
    ): JournalEntry {

        $entry->update(
            $data
        );

        return $entry->fresh();
    }

    public function delete(
        JournalEntry $entry
    ): bool {

        return $entry->delete();
    }
}