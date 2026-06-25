<?php

declare(strict_types=1);

namespace App\Domains\ServiceContracts\Jobs;

use App\Domains\ServiceContracts\Models\ServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessServiceContractJob implements ShouldQueue
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
        ServiceContract::query()
            ->where('company_id', $this->companyId)
            ->find($this->recordId);
    }
}
