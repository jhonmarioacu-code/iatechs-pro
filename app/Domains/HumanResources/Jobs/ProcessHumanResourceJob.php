<?php

declare(strict_types=1);

namespace App\Domains\HumanResources\Jobs;

use App\Domains\HumanResources\Models\HumanResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessHumanResourceJob implements ShouldQueue
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
        HumanResource::query()
            ->where('company_id', $this->companyId)
            ->find($this->recordId);
    }
}
