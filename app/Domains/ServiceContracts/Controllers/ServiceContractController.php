<?php

declare(strict_types=1);

namespace App\Domains\ServiceContracts\Controllers;

use App\Domains\ServiceContracts\Models\ServiceContract;
use App\Domains\ServiceContracts\Requests\StoreServiceContractRequest;
use App\Domains\ServiceContracts\Requests\UpdateServiceContractRequest;
use App\Domains\ServiceContracts\Resources\ServiceContractResource;
use App\Domains\ServiceContracts\Services\ServiceContractService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ServiceContractController extends Controller
{
    public function __construct(
        protected ServiceContractService $service
    ) {}

    public function index()
    {
        return ServiceContractResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreServiceContractRequest $request
    ): ServiceContractResource {

        return new ServiceContractResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        ServiceContract $serviceContract
    ): ServiceContractResource {

        return new ServiceContractResource($serviceContract);
    }

    public function update(
        UpdateServiceContractRequest $request,
        ServiceContract $serviceContract
    ): ServiceContractResource {

        return new ServiceContractResource(
            $this->service->update($serviceContract, $request->validated())
        );
    }

    public function destroy(
        ServiceContract $serviceContract
    ): JsonResponse {

        $this->service->delete($serviceContract);

        return response()->json([
            'success' => true,
            'message' => 'ServiceContract deleted successfully',
        ]);
    }
}
