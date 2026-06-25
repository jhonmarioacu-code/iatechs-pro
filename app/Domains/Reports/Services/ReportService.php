<?php

declare(strict_types=1);

namespace App\Domains\Reports\Services;

use Illuminate\Support\Str;

use App\Domains\Reports\Models\Report;
use App\Domains\Reports\Repositories\ReportRepository;

class ReportService
{
    public function __construct(
        private ReportRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    ) {
        return $this->repository
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Report {

        return $this->repository->create([

            'uuid' => (string) Str::uuid(),

            'company_id' => $data['company_id'],

            'user_id' => auth()->id(),

            'name' => $data['name'],

            'type' => $data['type'],

            'filters' => $data['filters'] ?? [],

            'status' => 'PENDING',

            'total_records' => 0
        ]);
    }

    public function markProcessing(
        Report $report
    ): Report {

        return $this->repository->update(
            $report,
            [
                'status' => 'PROCESSING'
            ]
        );
    }

    public function markCompleted(
        Report $report,
        int $records = 0
    ): Report {

        return $this->repository->update(
            $report,
            [
                'status' => 'COMPLETED',

                'generated_at' => now(),

                'total_records' => $records
            ]
        );
    }

    public function markFailed(
        Report $report
    ): Report {

        return $this->repository->update(
            $report,
            [
                'status' => 'FAILED'
            ]
        );
    }
}
