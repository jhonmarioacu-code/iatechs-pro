<?php

declare(strict_types=1);

namespace App\Domains\ServiceContracts\Events;

use App\Domains\ServiceContracts\Models\ServiceContract;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceContractCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ServiceContract $serviceContract
    ) {}
}
