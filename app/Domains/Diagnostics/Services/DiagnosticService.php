<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Domains\Diagnostics\Models\Diagnostic;
use App\Domains\Diagnostics\Repositories\DiagnosticRepository;

class DiagnosticService
{
    public function __construct(
        private DiagnosticRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    ) {
        return $this->repository
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Diagnostic {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            $data['diagnostic_code'] =
                $this->generateCode();

            $data['status'] =
                'PENDING';

            return $this->repository
                ->create($data);
        });
    }

    public function update(
        Diagnostic $diagnostic,
        array $data
    ): Diagnostic {

        return $this->repository
            ->update(
                $diagnostic,
                $data
            );
    }

    public function start(
        Diagnostic $diagnostic
    ): Diagnostic {

        return $this->repository
            ->update(
                $diagnostic,
                [
                    'status' => 'IN_PROGRESS',
                    'started_at' => now(),
                ]
            );
    }

    public function complete(
        Diagnostic $diagnostic,
        array $data
    ): Diagnostic {

        return $this->repository
            ->update(
                $diagnostic,
                [
                    'status' => 'COMPLETED',
                    'diagnostic_result' =>
                        $data['diagnostic_result'],

                    'recommended_solution' =>
                        $data['recommended_solution'],

                    'estimated_cost' =>
                        $data['estimated_cost'],

                    'estimated_hours' =>
                        $data['estimated_hours'],

                    'finished_at' => now(),
                ]
            );
    }

    public function cancel(
        Diagnostic $diagnostic
    ): Diagnostic {

        return $this->repository
            ->update(
                $diagnostic,
                [
                    'status' => 'CANCELLED'
                ]
            );
    }

    public function delete(
        Diagnostic $diagnostic
    ): bool {

        return $this->repository
            ->delete($diagnostic);
    }

    private function generateCode(): string
    {
        do {

            $code =
                'DG-' .
                date('Y') .
                '-' .
                strtoupper(
                    Str::random(8)
                );

        } while (
            $this->repository
                ->existsDiagnosticCode(
                    $code
                )
        );

        return $code;
    }
}