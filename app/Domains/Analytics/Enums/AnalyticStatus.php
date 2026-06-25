<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Enums;

enum AnalyticStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
