<?php

declare(strict_types=1);

namespace App\Domains\Compliance\Controllers;

use App\Domains\Compliance\Models\ComplianceRecord;
use App\Domains\Compliance\Requests\StoreComplianceRecordRequest;
use App\Domains\Compliance\Requests\UpdateComplianceRecordRequest;
use App\Domains\Compliance\Resources\ComplianceRecordResource;
use App\Domains\Compliance\Services\ComplianceRecordService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ComplianceRecordController extends Controller
{
    public function __construct(
        protected ComplianceRecordService $service
    ) {}

    public function index()
    {
        return ComplianceRecordResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreComplianceRecordRequest $request
    ): ComplianceRecordResource {

        return new ComplianceRecordResource(
            $this->service->create($request->validated())
        );
    }

    public function show(
        ComplianceRecord $complianceRecord
    ): ComplianceRecordResource {

        return new ComplianceRecordResource($complianceRecord);
    }

    public function update(
        UpdateComplianceRecordRequest $request,
        ComplianceRecord $complianceRecord
    ): ComplianceRecordResource {

        return new ComplianceRecordResource(
            $this->service->update($complianceRecord, $request->validated())
        );
    }

    public function destroy(
        ComplianceRecord $complianceRecord
    ): JsonResponse {

        $this->service->delete($complianceRecord);

        return response()->json([
            'success' => true,
            'message' => 'ComplianceRecord deleted successfully',
        ]);
    }
}
