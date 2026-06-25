<?php

declare(strict_types=1);

namespace App\Domains\Purchases\Enums;

enum PurchaseStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
