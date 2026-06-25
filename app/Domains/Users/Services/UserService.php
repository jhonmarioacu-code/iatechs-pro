<?php

declare(strict_types=1);

namespace App\Domains\Users\Services;

use Illuminate\Support\Str;
use App\Domains\Users\Models\User;
use App\Domains\Users\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function create(
        array $data
    ): User {

        $data['uuid'] = Str::uuid();

        $role = $data['role'];

        unset($data['role']);

        $user = $this->repository
            ->create($data);

        $user->assignRole($role);

        return $user;
    }

    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository->paginate(
            $perPage
        );
    }

    public function paginateForActor(
        User $actor,
        int $perPage = 20
    )
    {
        return $this->repository->paginateForActor(
            $actor,
            $perPage
        );
    }

    public function update(
        User $user,
        array $data
    ): User {
        return $this->repository->update(
            $user,
            $data
        );
    }

    public function delete(
        User $user
    ): bool {
        return $this->repository->delete(
            $user
        );
    }
}
