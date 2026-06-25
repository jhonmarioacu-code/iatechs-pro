<?php

declare(strict_types=1);

namespace App\Domains\DocumentManagement\Services;

use App\Domains\DocumentManagement\Enums\ManagedDocumentStatus;
use App\Domains\DocumentManagement\Models\ManagedDocument;
use App\Domains\DocumentManagement\Repositories\ManagedDocumentRepository;
use Illuminate\Support\Str;

class ManagedDocumentService
{
    public function __construct(
        private ManagedDocumentRepository $repository
    ) {}

    public function paginate(int $perPage = 20)
    {
        return $this->repository->paginate($perPage);
    }

    public function create(array $data): ManagedDocument
    {
        $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
        $data['status'] = $data['status'] ?? ManagedDocumentStatus::Draft->value;

        return $this->repository->create($data);
    }

    public function update(ManagedDocument $managedDocument, array $data): ManagedDocument
    {
        return $this->repository->update($managedDocument, $data);
    }

    public function delete(ManagedDocument $managedDocument): bool
    {
        return $this->repository->delete($managedDocument);
    }
}
