<?php

declare(strict_types=1);

namespace App\Domains\Projects\Policies;

use App\Domains\Projects\Models\Project;
use App\Domains\Users\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('projects.view');
    }

    public function view(User $user, Project $project): bool
    {
        return $user->can('projects.view')
            && $user->company_id === $project->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('projects.create');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can('projects.update')
            && $user->company_id === $project->company_id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can('projects.delete')
            && $user->company_id === $project->company_id;
    }
}
