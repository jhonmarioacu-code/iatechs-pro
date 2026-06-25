<?php

declare(strict_types=1);

namespace App\Domains\SystemSettings\Services;

use App\Domains\SystemSettings\Enums\SystemSettingStatus;
use App\Domains\SystemSettings\Models\SystemSetting;
use App\Domains\SystemSettings\Repositories\SystemSettingRepository;
use Illuminate\Support\Str;

class SystemSettingService
{
    public function __construct(
        private SystemSettingRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): SystemSetting
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? SystemSettingStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(SystemSetting $systemSetting, array $data): SystemSetting
    {
        return $this->repository->update($systemSetting, $data);
    }

    public function delete(SystemSetting $systemSetting): bool
    {
        return $this->repository->delete($systemSetting);
    }
}
