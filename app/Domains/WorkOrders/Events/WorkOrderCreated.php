<?php

declare(strict_types=1);

namespace App\Domains\WorkOrders\Events;

use App\Domains\WorkOrders\Models\WorkOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkOrderCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly WorkOrder $workOrder
    ) {}
}
