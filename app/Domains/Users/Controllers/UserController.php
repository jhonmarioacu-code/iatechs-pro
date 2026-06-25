<?php

declare(strict_types=1);

namespace App\Domains\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\UserService;
use App\Domains\Users\Requests\StoreUserRequest;
use App\Domains\Users\Requests\UpdateUserRequest;
use App\Domains\Users\Resources\UserResource;

class UserController extends Controller
{
    public function __construct(
        private UserService $service
    ) {}

    public function index(
        Request $request
    )
    {
        $this->authorize('viewAny', User::class);

        return UserResource::collection(
            $this->service->paginateForActor(
                $request->user()
            )
        );
    }

    public function store(
        StoreUserRequest $request
    )
    {
        $this->authorize('create', User::class);

        return new UserResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        User $user
    )
    {
        $this->authorize('view', $user);

        return new UserResource(
            $user
        );
    }

    public function update(
        UpdateUserRequest $request,
        User $user
    )
    {
        $this->authorize('update', $user);

        return new UserResource(
            $this->service->update(
                $user,
                $request->validated()
            )
        );
    }

    public function destroy(
        User $user
    )
    {
        $this->authorize('delete', $user);

        $this->service->delete(
            $user
        );

        return response()->json([
            'success' => true
        ]);
    }
}
