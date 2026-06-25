<?php

declare(strict_types=1);

namespace App\Support\Repositories\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ProvidesRepositoryDefaults
{
    protected function query()
    {
        $modelClass = $this->repositoryModelClass();

        return $modelClass::query();
    }

    protected function repositoryModelClass(): string
    {
        $repositoryClass = static::class;
        $modelClass = str_replace(
            ['\\Repositories\\', 'Repository'],
            ['\\Models\\', ''],
            $repositoryClass
        );

        if (!class_exists($modelClass)) {
            throw new ModelNotFoundException(
                sprintf('Repository model [%s] was not found.', $modelClass)
            );
        }

        return $modelClass;
    }

    public function findOrFail(
        int $id
    ): Model {
        return $this->query()->findOrFail($id);
    }

    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function paginate(
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->query()
            ->latest()
            ->paginate($perPage);
    }

    public function find(
        int $id
    ): ?Model {
        return $this->query()->find($id);
    }

    public function create(
        array $data
    ): Model {
        return $this->query()->create($data);
    }

    public function update(
        Model $model,
        array $data
    ): Model {
        $model->update($data);

        return $model->refresh();
    }

    public function delete(
        Model $model
    ): bool {
        return (bool) $model->delete();
    }
}
