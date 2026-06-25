<?php

declare(strict_types=1);

namespace App\Domains\Customers\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Tenant\Managers\TenantManager;

use App\Domains\Customers\Models\Customer;
use App\Domains\Customers\Repositories\CustomerRepository;

class CustomerService
{
    public function __construct(
        private CustomerRepository $repository,
        private TenantManager $tenant
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function createCustomer(
        array $data
    ): Customer {

        return DB::transaction(function () use ($data) {

            if (
                !empty($data['document_number']) &&
                $this->repository->existsDocument(
                    $data['document_number']
                )
            ) {
                throw new Exception(
                    'Document already exists.'
                );
            }

            if (
                !empty($data['email']) &&
                $this->repository->existsEmail(
                    $data['email']
                )
            ) {
                throw new Exception(
                    'Email already exists.'
                );
            }

            $data['uuid'] =
                (string) Str::uuid();

            $data['customer_code'] =
                $this->generateCustomerCode();

            $data['company_id'] =
                $this->tenant->companyId();

            $data['is_active'] =
                $data['is_active'] ?? true;

            return $this->repository
                ->create($data);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function updateCustomer(
        Customer $customer,
        array $data
    ): Customer {

        return DB::transaction(function () use (
            $customer,
            $data
        ) {

            if (
                isset($data['document_number']) &&
                $data['document_number'] !==
                $customer->document_number
            ) {

                $exists =
                    $this->repository
                        ->existsDocument(
                            $data['document_number']
                        );

                if ($exists) {

                    throw new Exception(
                        'Document already exists.'
                    );
                }
            }

            return $this->repository
                ->update(
                    $customer,
                    $data
                );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteCustomer(
        Customer $customer
    ): bool {

        return $this->repository
            ->delete($customer);
    }

    /*
    |--------------------------------------------------------------------------
    | Activate
    |--------------------------------------------------------------------------
    */

    public function activateCustomer(
        Customer $customer
    ): Customer {

        return $this->repository
            ->update(
                $customer,
                [
                    'is_active' => true
                ]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Deactivate
    |--------------------------------------------------------------------------
    */

    public function deactivateCustomer(
        Customer $customer
    ): Customer {

        return $this->repository
            ->update(
                $customer,
                [
                    'is_active' => false
                ]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(
        string $term
    )
    {
        return $this->repository
            ->search($term);
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): ?Customer {

        return $this->repository
            ->find($id);
    }

    public function findByUuid(
        string $uuid
    ): ?Customer {

        return $this->repository
            ->findByUuid($uuid);
    }

    public function findByCustomerCode(
        string $code
    ): ?Customer {

        return $this->repository
            ->findByCustomerCode(
                $code
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function generateCustomerCode(): string
    {
        return 'CUS-'
            . date('Y')
            . '-'
            . strtoupper(
                Str::random(6)
            );
    }
}