<?php

declare(strict_types=1);

namespace App\Domains\DocumentManagement\Enums;

enum ManagedDocumentStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';
}
