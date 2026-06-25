<?php

declare(strict_types=1);

namespace App\Domains\ServiceContracts\Enums;

enum ServiceContractStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
