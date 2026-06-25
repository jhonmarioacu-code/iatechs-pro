<?php

declare(strict_types=1);

namespace App\Domains\FileManager\Enums;

enum FileManagerStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
