<?php

declare(strict_types=1);

namespace App\Domains\BusinessIntelligence\Enums;

enum BusinessMetricStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
