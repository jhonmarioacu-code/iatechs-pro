<?php

declare(strict_types=1);

namespace App\Domains\Assets\Services;

use App\Domains\Assets\Enums\AssetStatus;
use App\Domains\Assets\Models\Asset;
use App\Domains\Assets\Repositories\AssetRepository;
use Illuminate\Support\Str;

class AssetService
{
    public function __construct(
        private AssetRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): Asset
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? AssetStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(Asset $asset, array $data): Asset
    {
        return $this->repository->update($asset, $data);
    }

    public function delete(Asset $asset): bool
    {
        return $this->repository->delete($asset);
    }
}
