<?php

declare(strict_types=1);

namespace App\Domains\TechnicianSchedules\Enums;

enum TechnicianScheduleStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
