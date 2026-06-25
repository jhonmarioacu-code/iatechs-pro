<?php

declare(strict_types=1);

namespace App\Domains\Compliance\Enums;

enum ComplianceRecordStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
