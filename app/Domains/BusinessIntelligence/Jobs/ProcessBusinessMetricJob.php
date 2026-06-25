<?php

declare(strict_types=1);

namespace App\Domains\BusinessIntelligence\Jobs;

use App\Domains\BusinessIntelligence\Models\BusinessMetric;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBusinessMetricJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly int $companyId,
        public readonly int $recordId
    ) {}

    public function handle(): void
    {
        BusinessMetric::query()
            ->where('company_id', $this->companyId)
            ->find($this->recordId);
    }
}
