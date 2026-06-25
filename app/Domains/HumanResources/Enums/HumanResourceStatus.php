<?php

declare(strict_types=1);

namespace App\Domains\HumanResources\Enums;

enum HumanResourceStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
