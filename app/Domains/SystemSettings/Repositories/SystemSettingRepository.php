<?php

declare(strict_types=1);

namespace App\Domains\SystemSettings\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\SystemSettings\Models\SystemSetting;

class SystemSettingRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return SystemSetting::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): SystemSetting
    {
        return SystemSetting::create($data);
    }

    public function update(SystemSetting $systemSetting, array $data): SystemSetting
    {
        $systemSetting->update($data);

        return $systemSetting->refresh();
    }

    public function delete(SystemSetting $systemSetting): bool
    {
        return (bool) $systemSetting->delete();
    }
}
