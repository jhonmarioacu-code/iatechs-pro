<?php

declare(strict_types=1);

namespace App\Domains\Companies\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Companies\Models\Company;

use App\Domains\Companies\Services\CompanyService;

use App\Domains\Companies\Requests\StoreCompanyRequest;
use App\Domains\Companies\Requests\UpdateCompanyRequest;

use App\Domains\Companies\Resources\CompanyResource;

use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $service
    ) {}

    /**
     * List Companies
     */
    public function index()
    {
        $this->authorize('viewAny', Company::class);

        return CompanyResource::collection(
            $this->service->paginate()
        );
    }

    /**
     * Store Company
     */
    public function store(
        StoreCompanyRequest $request
    ): CompanyResource {
        $this->authorize('create', Company::class);

        $company = $this->service
            ->create(
                $request->validated()
            );

        return new CompanyResource(
            $company
        );
    }

    /**
     * Show Company
     */
    public function show(
        Company $company
    ): CompanyResource {
        $this->authorize('view', $company);

        return new CompanyResource(
            $company
        );
    }

    /**
     * Update Company
     */
    public function update(
        UpdateCompanyRequest $request,
        Company $company
    ): CompanyResource {
        $this->authorize('update', $company);

        $company = $this->service
            ->update(
                $company,
                $request->validated()
            );

        return new CompanyResource(
            $company
        );
    }

    /**
     * Delete Company
     */
    public function destroy(
        Company $company
    ): JsonResponse {
        $this->authorize('delete', $company);

        $this->service
            ->delete($company);

        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully'
        ]);
    }

    /**
     * Activate Company
     */
    public function activate(
        Company $company
    ): CompanyResource {
        $this->authorize('activate', $company);

        return new CompanyResource(
            $this->service
                ->activate($company)
        );
    }

    /**
     * Suspend Company
     */
    public function suspend(
        Company $company
    ): CompanyResource {
        $this->authorize('suspend', $company);

        return new CompanyResource(
            $this->service
                ->suspend($company)
        );
    }

    /**
     * Cancel Company
     */
    public function cancel(
        Company $company
    ): CompanyResource {
        $this->authorize('delete', $company);

        return new CompanyResource(
            $this->service
                ->cancel($company)
        );
    }
}
