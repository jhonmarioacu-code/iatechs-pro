<?php

declare(strict_types=1);

namespace App\Domains\Customers\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Domains\Customers\Models\Customer;
use App\Domains\Customers\Services\CustomerService;
use App\Domains\Customers\Requests\StoreCustomerRequest;
use App\Domains\Customers\Requests\UpdateCustomerRequest;
use App\Domains\Customers\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->authorize(
            'viewAny',
            Customer::class
        );

        return CustomerResource::collection(
            $this->service->paginate()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreCustomerRequest $request
    ): CustomerResource {

        $this->authorize(
            'create',
            Customer::class
        );

        return new CustomerResource(
            $this->service->createCustomer(
                $request->validated()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Customer $customer
    ): CustomerResource {

        $this->authorize(
            'view',
            $customer
        );

        return new CustomerResource(
            $customer->load([
                'company',
                'branch'
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateCustomerRequest $request,
        Customer $customer
    ): CustomerResource {

        $this->authorize(
            'update',
            $customer
        );

        return new CustomerResource(
            $this->service->updateCustomer(
                $customer,
                $request->validated()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Customer $customer
    ): JsonResponse {

        $this->authorize(
            'delete',
            $customer
        );

        $this->service->deleteCustomer(
            $customer
        );

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Activate
    |--------------------------------------------------------------------------
    */

    public function activate(
        Customer $customer
    ): CustomerResource {

        $this->authorize(
            'activate',
            $customer
        );

        return new CustomerResource(
            $this->service->activateCustomer(
                $customer
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Deactivate
    |--------------------------------------------------------------------------
    */

    public function deactivate(
        Customer $customer
    ): CustomerResource {

        $this->authorize(
            'deactivate',
            $customer
        );

        return new CustomerResource(
            $this->service->deactivateCustomer(
                $customer
            )
        );
    }
}