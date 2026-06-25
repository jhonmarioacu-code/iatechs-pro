<?php

declare(strict_types=1);

namespace App\Domains\WorkOrders\Enums;

enum WorkOrderStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
