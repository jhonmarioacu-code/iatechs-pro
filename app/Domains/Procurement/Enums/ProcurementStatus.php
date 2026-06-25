<?php

declare(strict_types=1);

namespace App\Domains\Procurement\Enums;

enum ProcurementStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
