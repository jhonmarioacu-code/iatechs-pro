<?php

declare(strict_types=1);

namespace App\Domains\HumanResources\Events;

use App\Domains\HumanResources\Models\HumanResource;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HumanResourceCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly HumanResource $humanResource
    ) {}
}
