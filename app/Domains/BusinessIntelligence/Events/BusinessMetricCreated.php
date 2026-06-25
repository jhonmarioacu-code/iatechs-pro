<?php

declare(strict_types=1);

namespace App\Domains\BusinessIntelligence\Events;

use App\Domains\BusinessIntelligence\Models\BusinessMetric;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusinessMetricCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly BusinessMetric $businessMetric
    ) {}
}
