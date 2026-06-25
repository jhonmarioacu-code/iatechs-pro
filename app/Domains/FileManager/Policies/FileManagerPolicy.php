<?php

declare(strict_types=1);

namespace App\Domains\FileManager\Policies;

use App\Domains\FileManager\Models\FileManager;
use App\Domains\Users\Models\User;

class FileManagerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('file-manager.view');
    }

    public function view(User $user, FileManager $fileManager): bool
    {
        return $user->can('file-manager.view')
            && $user->company_id === $fileManager->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('file-manager.create');
    }

    public function update(User $user, FileManager $fileManager): bool
    {
        return $user->can('file-manager.update')
            && $user->company_id === $fileManager->company_id;
    }

    public function delete(User $user, FileManager $fileManager): bool
    {
        return $user->can('file-manager.delete')
            && $user->company_id === $fileManager->company_id;
    }
}
