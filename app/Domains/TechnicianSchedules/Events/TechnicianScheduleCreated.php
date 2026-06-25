<?php

declare(strict_types=1);

namespace App\Domains\TechnicianSchedules\Events;

use App\Domains\TechnicianSchedules\Models\TechnicianSchedule;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TechnicianScheduleCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly TechnicianSchedule $technicianSchedule
    ) {}
}
