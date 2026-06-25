<?php

declare(strict_types=1);

namespace App\Domains\Suppliers\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Suppliers\Models\Supplier;
use App\Domains\Suppliers\Repositories\SupplierRepository;

class SupplierService
{
    public function __construct(
        private SupplierRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Supplier {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            $data['status'] =
                $data['status']
                ??
                'ACTIVE';

            return $this->repository
                ->create($data);
        });
    }

    public function update(
        Supplier $supplier,
        array $data
    ): Supplier {

        return $this->repository
            ->update(
                $supplier,
                $data
            );
    }

    public function activate(
        Supplier $supplier
    ): Supplier {

        return $this->repository
            ->update(
                $supplier,
                [
                    'status' => 'ACTIVE'
                ]
            );
    }

    public function deactivate(
        Supplier $supplier
    ): Supplier {

        return $this->repository
            ->update(
                $supplier,
                [
                    'status' => 'INACTIVE'
                ]
            );
    }

    public function block(
        Supplier $supplier
    ): Supplier {

        return $this->repository
            ->update(
                $supplier,
                [
                    'status' => 'BLOCKED'
                ]
            );
    }
}