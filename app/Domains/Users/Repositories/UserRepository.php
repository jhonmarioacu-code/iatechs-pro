<?php

declare(strict_types=1);

namespace App\Domains\Users\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Users\Models\User;

class UserRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(
        int $perPage = 20
    )
    {
        return User::latest()
            ->paginate($perPage);
    }

    public function paginateForActor(
        User $actor,
        int $perPage = 20
    )
    {
        return User::query()
            ->when(
                !$actor->hasRole('super_admin'),
                fn ($query) => $query->where('company_id', $actor->company_id)
            )
            ->latest()
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): User {

        return User::create($data);
    }

    public function find(
        int $id
    ): ?User {

        return User::find($id);
    }

    public function update(
        User $user,
        array $data
    ): User {
        $user->update($data);

        return $user->refresh();
    }

    public function delete(
        User $user
    ): bool {
        return (bool) $user->delete();
    }
}
