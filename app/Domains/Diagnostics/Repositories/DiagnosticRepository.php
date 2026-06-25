<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Diagnostics\Models\Diagnostic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DiagnosticRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Diagnostic::query()
            ->with([
                'ticket',
                'technician'
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Diagnostic {

        return Diagnostic::query()
            ->find($id);
    }

    public function findByUuid(
        string $uuid
    ): ?Diagnostic {

        return Diagnostic::query()
            ->where('uuid', $uuid)
            ->first();
    }

    public function create(
        array $data
    ): Diagnostic {

        return Diagnostic::create($data);
    }

    public function update(
        Diagnostic $diagnostic,
        array $data
    ): Diagnostic {

        $diagnostic->update($data);

        return $diagnostic->refresh();
    }

    public function delete(
        Diagnostic $diagnostic
    ): bool {

        return (bool) $diagnostic->delete();
    }

    public function findByDiagnosticCode(
        string $code
    ): ?Diagnostic {

        return Diagnostic::query()
            ->where(
                'diagnostic_code',
                $code
            )
            ->first();
    }

    public function existsDiagnosticCode(
        string $code
    ): bool {

        return Diagnostic::query()
            ->where(
                'diagnostic_code',
                $code
            )
            ->exists();
    }
}