<?php

declare(strict_types=1);

namespace App\Domains\Procurement\Events;

use App\Domains\Procurement\Models\Procurement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcurementCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Procurement $procurement
    ) {}
}
