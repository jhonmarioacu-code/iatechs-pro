<?php

declare(strict_types=1);

namespace App\Domains\FileManager\Services;

use App\Domains\FileManager\Enums\FileManagerStatus;
use App\Domains\FileManager\Models\FileManager;
use App\Domains\FileManager\Repositories\FileManagerRepository;
use Illuminate\Support\Str;

class FileManagerService
{
    public function __construct(
        private FileManagerRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): FileManager
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? FileManagerStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(FileManager $fileManager, array $data): FileManager
    {
        return $this->repository->update($fileManager, $data);
    }

    public function delete(FileManager $fileManager): bool
    {
        return $this->repository->delete($fileManager);
    }
}
