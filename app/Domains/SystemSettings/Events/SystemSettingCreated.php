<?php

declare(strict_types=1);

namespace App\Domains\SystemSettings\Events;

use App\Domains\SystemSettings\Models\SystemSetting;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemSettingCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly SystemSetting $systemSetting
    ) {}
}
