<?php

declare(strict_types=1);

namespace App\Domains\Reports\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Reports\Models\Report;
use App\Domains\Reports\Services\ReportService;

use App\Domains\Reports\Requests\GenerateReportRequest;
use App\Domains\Reports\Resources\ReportResource;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Report::class);

        return ReportResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        GenerateReportRequest $request
    )
    {
        $this->authorize('create', Report::class);

        return new ReportResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        Report $report
    )
    {
        $this->authorize('view', $report);

        return new ReportResource(
            $report->load([
                'exports'
            ])
        );
    }
}
