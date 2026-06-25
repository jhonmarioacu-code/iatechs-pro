<?php

declare(strict_types=1);

namespace App\Domains\Customers\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Customers\Models\Customer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerRepository
{
    use ProvidesRepositoryDefaults;

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Customer::query()

            ->with([
                'company',
                'branch',
            ])

            ->latest()

            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | Get All
    |--------------------------------------------------------------------------
    */

    public function all(): Collection
    {
        return Customer::query()

            ->with([
                'company',
                'branch',
            ])

            ->orderBy('first_name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): ?Customer {

        return Customer::query()

            ->with([
                'company',
                'branch',
            ])

            ->find($id);
    }

    public function findByUuid(
        string $uuid
    ): ?Customer {

        return Customer::query()

            ->where(
                'uuid',
                $uuid
            )

            ->first();
    }

    public function findByCustomerCode(
        string $customerCode
    ): ?Customer {

        return Customer::query()

            ->where(
                'customer_code',
                $customerCode
            )

            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(
        string $term,
        int $limit = 20
    ): Collection {

        return Customer::query()

            ->where(function ($query) use ($term) {

                $query

                    ->where(
                        'first_name',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'last_name',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'company_name',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'customer_code',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'document_number',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'email',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'phone',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'mobile',
                        'like',
                        "%{$term}%"
                    );
            })

            ->limit($limit)

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    public function active(): Collection
    {
        return Customer::query()

            ->where(
                'is_active',
                true
            )

            ->orderBy('first_name')

            ->get();
    }

    public function getByCompany(
        int $companyId
    ): Collection {

        return Customer::query()

            ->where(
                'company_id',
                $companyId
            )

            ->orderBy('first_name')

            ->get();
    }

    public function getByBranch(
        int $branchId
    ): Collection {

        return Customer::query()

            ->where(
                'branch_id',
                $branchId
            )

            ->orderBy('first_name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): Customer {

        return Customer::create(
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Customer $customer,
        array $data
    ): Customer {

        $customer->update(
            $data
        );

        return $customer->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        Customer $customer
    ): bool {

        return (bool) $customer->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    public function existsDocument(
        string $documentNumber
    ): bool {

        return Customer::query()

            ->where(
                'document_number',
                $documentNumber
            )

            ->exists();
    }

    public function existsEmail(
        string $email
    ): bool {

        return Customer::query()

            ->where(
                'email',
                $email
            )

            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Counters
    |--------------------------------------------------------------------------
    */

    public function count(): int
    {
        return Customer::query()
            ->count();
    }

    public function countByBranch(
        int $branchId
    ): int {

        return Customer::query()

            ->where(
                'branch_id',
                $branchId
            )

            ->count();
    }
}