<?php

declare(strict_types=1);

namespace App\Domains\Assets\Policies;

use App\Domains\Assets\Models\Asset;
use App\Domains\Users\Models\User;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('assets.view');
    }

    public function view(User $user, Asset $asset): bool
    {
        return $user->can('assets.view')
            && $user->company_id === $asset->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('assets.create');
    }

    public function update(User $user, Asset $asset): bool
    {
        return $user->can('assets.update')
            && $user->company_id === $asset->company_id;
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $user->can('assets.delete')
            && $user->company_id === $asset->company_id;
    }
}
