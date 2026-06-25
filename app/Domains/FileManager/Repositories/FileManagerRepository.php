<?php

declare(strict_types=1);

namespace App\Domains\FileManager\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\FileManager\Models\FileManager;

class FileManagerRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return FileManager::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): FileManager
    {
        return FileManager::create($data);
    }

    public function update(FileManager $fileManager, array $data): FileManager
    {
        $fileManager->update($data);

        return $fileManager->refresh();
    }

    public function delete(FileManager $fileManager): bool
    {
        return (bool) $fileManager->delete();
    }
}
