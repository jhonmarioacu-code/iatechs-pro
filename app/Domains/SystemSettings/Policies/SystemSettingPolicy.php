<?php

declare(strict_types=1);

namespace App\Domains\SystemSettings\Policies;

use App\Domains\SystemSettings\Models\SystemSetting;
use App\Domains\Users\Models\User;

class SystemSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('system-settings.view');
    }

    public function view(User $user, SystemSetting $systemSetting): bool
    {
        return $user->can('system-settings.view')
            && $user->company_id === $systemSetting->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('system-settings.create');
    }

    public function update(User $user, SystemSetting $systemSetting): bool
    {
        return $user->can('system-settings.update')
            && $user->company_id === $systemSetting->company_id;
    }

    public function delete(User $user, SystemSetting $systemSetting): bool
    {
        return $user->can('system-settings.delete')
            && $user->company_id === $systemSetting->company_id;
    }
}
