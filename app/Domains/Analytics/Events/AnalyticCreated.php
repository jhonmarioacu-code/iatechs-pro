<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Events;

use App\Domains\Analytics\Models\Analytic;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnalyticCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Analytic $analytic
    ) {}
}
