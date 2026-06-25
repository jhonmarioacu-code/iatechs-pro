<?php

declare(strict_types=1);

namespace App\Domains\Accounting\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Accounting\Models\Account;

class AccountRepository
{
    use ProvidesRepositoryDefaults;

    public function query()
    {
        return Account::query();
    }

    public function find(
        int $id
    ): ?Account {

        return Account::find($id);
    }

    public function create(
        array $data
    ): Account {

        return Account::create(
            $data
        );
    }

    public function update(
        Account $account,
        array $data
    ): Account {

        $account->update(
            $data
        );

        return $account->fresh();
    }

    public function delete(
        Account $account
    ): bool {

        return $account->delete();
    }
}