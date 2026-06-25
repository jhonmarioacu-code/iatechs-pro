<?php

declare(strict_types=1);

namespace App\Domains\CRM\Services;

use Illuminate\Support\Str;

use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\Opportunity;

use App\Domains\CRM\Repositories\LeadRepository;
use App\Domains\CRM\Repositories\OpportunityRepository;

class CRMService
{
    public function __construct(

        protected LeadRepository $leads,

        protected OpportunityRepository $opportunities
    ) {}

    public function paginateLeads(
        int $perPage = 20
    ) {
        return $this->leads
            ->query()
            ->latest()
            ->paginate($perPage);
    }

    public function paginateOpportunities(
        int $perPage = 20
    ) {
        return $this->opportunities
            ->query()
            ->latest()
            ->paginate($perPage);
    }

    public function pipelineSummary(): array
    {
        $stages = [
            'prospecting',
            'qualification',
            'proposal',
            'negotiation',
            'won',
            'lost',
        ];

        return collect($stages)
            ->mapWithKeys(fn (string $stage) => [
                $stage => $this->opportunities
                    ->query()
                    ->where('stage', $stage)
                    ->count(),
            ])
            ->all();
    }

    public function createLead(
        array $data
    ): Lead {

        $data['uuid'] = Str::uuid();

        return $this->leads->create(
            $data
        );
    }

    public function updateLead(
        Lead $lead,
        array $data
    ): Lead {

        return $this->leads->update(
            $lead,
            $data
        );
    }

    public function convertLead(
        Lead $lead,
        array $opportunityData
    ): Opportunity {

        $lead->update([

            'status' => 'converted'
        ]);

        $opportunityData['uuid'] =
            Str::uuid();

        $opportunityData['lead_id'] =
            $lead->id;

        return $this->opportunities
            ->create(
                $opportunityData
            );
    }

    public function createOpportunity(
        array $data
    ): Opportunity {

        $data['uuid'] = Str::uuid();

        return $this->opportunities
            ->create(
                $data
            );
    }

    public function updateOpportunity(
        Opportunity $opportunity,
        array $data
    ): Opportunity {

        return $this->opportunities
            ->update(
                $opportunity,
                $data
            );
    }

    public function markWon(
        Opportunity $opportunity
    ): Opportunity {

        return $this->opportunities
            ->update(
                $opportunity,
                [
                    'stage' => 'won'
                ]
            );
    }

    public function markLost(
        Opportunity $opportunity
    ): Opportunity {

        return $this->opportunities
            ->update(
                $opportunity,
                [
                    'stage' => 'lost'
                ]
            );
    }
}
