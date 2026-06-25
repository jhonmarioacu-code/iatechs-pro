<?php

declare(strict_types=1);

namespace App\Domains\Branches\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Branches\Models\Branch;
use App\Domains\Branches\Repositories\BranchRepository;

class BranchService
{
    public function __construct(
        private BranchRepository $repository
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function createBranch(
        array $data
    ): Branch {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            /*
            |--------------------------------------------------------------------------
            | First Branch
            |--------------------------------------------------------------------------
            */

            $mainBranch =
                $this->repository
                    ->getMainBranch();

            if (!$mainBranch) {

                $data['is_main'] = true;
            }

            /*
            |--------------------------------------------------------------------------
            | Change Main Branch
            |--------------------------------------------------------------------------
            */

            if (
                ($data['is_main'] ?? false) === true
            ) {

                $this->removeMainBranch();
            }

            return $this->repository
                ->create($data);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function updateBranch(
        Branch $branch,
        array $data
    ): Branch {

        return DB::transaction(function () use (
            $branch,
            $data
        ) {

            if (
                ($data['is_main'] ?? false) === true
            ) {

                $this->removeMainBranch();
            }

            return $this->repository
                ->update(
                    $branch,
                    $data
                );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteBranch(
        Branch $branch
    ): bool {

        if ($branch->is_main) {

            throw new Exception(
                'No se puede eliminar la sucursal principal.'
            );
        }

        return $this->repository
            ->delete($branch);
    }

    /*
    |--------------------------------------------------------------------------
    | Activate
    |--------------------------------------------------------------------------
    */

    public function activateBranch(
        Branch $branch
    ): Branch {

        return $this->repository
            ->update(
                $branch,
                [
                    'is_active' => true
                ]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Deactivate
    |--------------------------------------------------------------------------
    */

    public function deactivateBranch(
        Branch $branch
    ): Branch {

        if ($branch->is_main) {

            throw new Exception(
                'No se puede desactivar la sucursal principal.'
            );
        }

        return $this->repository
            ->update(
                $branch,
                [
                    'is_active' => false
                ]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): ?Branch {

        return $this->repository
            ->find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Main Branch
    |--------------------------------------------------------------------------
    */

    public function getMainBranch(): ?Branch
    {
        return $this->repository
            ->getMainBranch();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function removeMainBranch(): void
    {
        $mainBranch =
            $this->repository
                ->getMainBranch();

        if ($mainBranch) {

            $this->repository
                ->update(
                    $mainBranch,
                    [
                        'is_main' => false
                    ]
                );
        }
    }
}