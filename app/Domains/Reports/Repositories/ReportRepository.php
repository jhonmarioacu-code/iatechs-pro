<?php

declare(strict_types=1);

namespace App\Domains\Reports\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Reports\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Report::query()
            ->with([
                'company',
                'user'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Report {

        return Report::create(
            $data
        );
    }

    public function update(
        Report $report,
        array $data
    ): Report {

        $report->update($data);

        return $report->refresh();
    }

    public function find(
        int $id
    ): ?Report {

        return Report::with([
            'exports'
        ])->find($id);
    }
}