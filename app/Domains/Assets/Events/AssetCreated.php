<?php

declare(strict_types=1);

namespace App\Domains\Assets\Events;

use App\Domains\Assets\Models\Asset;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssetCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Asset $asset
    ) {}
}
