<?php

declare(strict_types=1);

namespace App\Domains\Branches\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Branches\Models\Branch;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BranchRepository
{
    use ProvidesRepositoryDefaults;

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Branch::query()
            ->with([
                'company'
            ])
            ->latest()
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | All
    |--------------------------------------------------------------------------
    */

    public function all(): Collection
    {
        return Branch::query()
            ->with([
                'company'
            ])
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): ?Branch {

        return Branch::query()
            ->with([
                'company'
            ])
            ->find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | UUID
    |--------------------------------------------------------------------------
    */

    public function findByUuid(
        string $uuid
    ): ?Branch {

        return Branch::query()
            ->where(
                'uuid',
                $uuid
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Main Branch
    |--------------------------------------------------------------------------
    */

    public function getMainBranch(): ?Branch
    {
        return Branch::query()
            ->where(
                'is_main',
                true
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): Branch {

        return Branch::create(
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Branch $branch,
        array $data
    ): Branch {

        $branch->update(
            $data
        );

        return $branch->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        Branch $branch
    ): bool {

        return (bool) $branch->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | Exists By Name
    |--------------------------------------------------------------------------
    */

    public function existsByName(
        string $name
    ): bool {

        return Branch::query()
            ->where(
                'name',
                $name
            )
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Count
    |--------------------------------------------------------------------------
    */

    public function count(): int
    {
        return Branch::query()
            ->count();
    }
}