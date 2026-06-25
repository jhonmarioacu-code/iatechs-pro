<?php

declare(strict_types=1);

namespace App\Domains\Companies\Services;

use Illuminate\Support\Str;
use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Repositories\CompanyRepository;

class CompanyService
{
    public function __construct(
        private CompanyRepository $repository
    ) {}

    public function create(
        array $data
    ): Company {

        $data['uuid'] = (string) Str::uuid();

        $data['slug'] = Str::slug(
            $data['name']
        );

        $data['status'] = $data['status']
            ?? 'active';

        return $this->repository
            ->create($data);
    }

    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository->paginate(
            $perPage
        );
    }

    public function update(
        Company $company,
        array $data
    ): Company {

        return $this->repository
            ->update($company, $data);
    }

    public function delete(
        Company $company
    ): bool {

        return $this->repository
            ->delete($company);
    }

    public function activate(
        Company $company
    ): Company {

        return $this->repository
            ->update(
                $company,
                [
                    'status' => 'active'
                ]
            );
    }

    public function suspend(
        Company $company
    ): Company {

        return $this->repository
            ->update(
                $company,
                [
                    'status' => 'suspended'
                ]
            );
    }

    public function cancel(
        Company $company
    ): Company {

        return $this->repository
            ->update(
                $company,
                [
                    'status' => 'cancelled'
                ]
            );
    }
}
