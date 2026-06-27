<?php

declare(strict_types=1);

namespace App\Domains\CRM\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\Opportunity;
use App\Domains\CRM\Services\CRMService;

class CRMController extends Controller
{
    public function __construct(
        protected CRMService $service
    ) {}

    public function leads()
    {
        $this->authorize('viewAny', Lead::class);

        return $this->service
            ->paginateLeads();
    }

    public function opportunities()
    {
        $this->authorize('viewAny', Opportunity::class);

        return $this->service
            ->paginateOpportunities();
    }

    public function pipeline()
    {
        $this->authorize('viewAny', Opportunity::class);

        return $this->service
            ->pipelineSummary();
    }
}
