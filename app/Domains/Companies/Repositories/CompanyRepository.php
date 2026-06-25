<?php

declare(strict_types=1);

namespace App\Domains\Companies\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Companies\Models\Company;

class CompanyRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    )
    {
        return Company::query()
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Company {

        return Company::find($id);
    }

    public function findByUuid(
        string $uuid
    ): ?Company {

        return Company::where(
            'uuid',
            $uuid
        )->first();
    }

    public function create(
        array $data
    ): Company {

        return Company::create($data);
    }

    public function update(
        Company $company,
        array $data
    ): Company {

        $company->update($data);

        return $company->refresh();
    }

    public function delete(
        Company $company
    ): bool {

        return $company->delete();
    }

    public function search(
        string $term
    )
    {
        return Company::query()

            ->where('name', 'like', "%{$term}%")

            ->orWhere('email', 'like', "%{$term}%")

            ->orWhere('tax_id', 'like', "%{$term}%")

            ->paginate();
    }

    public function byStatus(
        string $status
    )
    {
        return Company::query()

            ->where(
                'status',
                $status
            )

            ->paginate();
    }
}