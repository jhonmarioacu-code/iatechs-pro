<?php

declare(strict_types=1);

namespace App\Domains\Purchases\Events;

use App\Domains\Purchases\Models\Purchase;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PurchaseCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Purchase $purchase
    ) {}
}
