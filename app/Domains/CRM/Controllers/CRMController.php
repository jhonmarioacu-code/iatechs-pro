<?php

declare(strict_types=1);

namespace App\Domains\CRM\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\CRM\Services\CRMService;

class CRMController extends Controller
{
    public function __construct(
        protected CRMService $service
    ) {}

    public function leads()
    {
        return $this->service
            ->paginateLeads();
    }

    public function opportunities()
    {
        return $this->service
            ->paginateOpportunities();
    }

    public function pipeline()
    {
        return $this->service
            ->pipelineSummary();
    }
}
