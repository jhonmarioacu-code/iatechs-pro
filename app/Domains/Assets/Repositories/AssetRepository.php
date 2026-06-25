<?php

declare(strict_types=1);

namespace App\Domains\Assets\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Assets\Models\Asset;

class AssetRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return Asset::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Asset
    {
        return Asset::create($data);
    }

    public function update(Asset $asset, array $data): Asset
    {
        $asset->update($data);

        return $asset->refresh();
    }

    public function delete(Asset $asset): bool
    {
        return (bool) $asset->delete();
    }
}
