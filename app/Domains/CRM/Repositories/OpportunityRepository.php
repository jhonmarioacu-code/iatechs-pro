<?php

declare(strict_types=1);

namespace App\Domains\CRM\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\CRM\Models\Opportunity;

class OpportunityRepository
{
    use ProvidesRepositoryDefaults;

    public function query()
    {
        return Opportunity::query();
    }

    public function find(
        int $id
    ): ?Opportunity {

        return Opportunity::find($id);
    }

    public function create(
        array $data
    ): Opportunity {

        return Opportunity::create($data);
    }

    public function update(
        Opportunity $opportunity,
        array $data
    ): Opportunity {

        $opportunity->update($data);

        return $opportunity->fresh();
    }

    public function delete(
        Opportunity $opportunity
    ): bool {

        return $opportunity->delete();
    }
}