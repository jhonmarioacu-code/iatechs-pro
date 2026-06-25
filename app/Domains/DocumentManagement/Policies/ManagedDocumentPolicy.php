<?php

declare(strict_types=1);

namespace App\Domains\DocumentManagement\Policies;

use App\Domains\DocumentManagement\Models\ManagedDocument;
use App\Domains\Users\Models\User;

class ManagedDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('document-management.view');
    }

    public function view(User $user, ManagedDocument $managedDocument): bool
    {
        return $user->can('document-management.view')
            && $user->company_id === $managedDocument->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('document-management.create');
    }

    public function update(User $user, ManagedDocument $managedDocument): bool
    {
        return $user->can('document-management.update')
            && $user->company_id === $managedDocument->company_id;
    }

    public function delete(User $user, ManagedDocument $managedDocument): bool
    {
        return $user->can('document-management.delete')
            && $user->company_id === $managedDocument->company_id;
    }
}
