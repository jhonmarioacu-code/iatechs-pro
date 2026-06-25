<?php

declare(strict_types=1);

namespace App\Domains\DocumentManagement\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\DocumentManagement\Models\ManagedDocument;

class ManagedDocumentRepository
{
    use ProvidesRepositoryDefaults;

    public function paginate(int $perPage = 20)
    {
        return ManagedDocument::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): ManagedDocument
    {
        return ManagedDocument::create($data);
    }

    public function update(ManagedDocument $managedDocument, array $data): ManagedDocument
    {
        $managedDocument->update($data);

        return $managedDocument->refresh();
    }

    public function delete(ManagedDocument $managedDocument): bool
    {
        return (bool) $managedDocument->delete();
    }
}
