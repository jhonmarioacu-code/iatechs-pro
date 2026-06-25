<?php

declare(strict_types=1);

namespace App\Domains\SystemSettings\Enums;

enum SystemSettingStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
