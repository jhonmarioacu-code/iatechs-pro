<?php

declare(strict_types=1);

namespace App\Domains\CRM\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\CRM\Models\Lead;

class LeadRepository
{
    use ProvidesRepositoryDefaults;

    public function query()
    {
        return Lead::query();
    }

    public function find(
        int $id
    ): ?Lead {

        return Lead::find($id);
    }

    public function create(
        array $data
    ): Lead {

        return Lead::create($data);
    }

    public function update(
        Lead $lead,
        array $data
    ): Lead {

        $lead->update($data);

        return $lead->fresh();
    }

    public function delete(
        Lead $lead
    ): bool {

        return $lead->delete();
    }
}