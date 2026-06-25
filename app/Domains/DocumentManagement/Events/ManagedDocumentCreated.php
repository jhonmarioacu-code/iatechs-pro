<?php

declare(strict_types=1);

namespace App\Domains\DocumentManagement\Events;

use App\Domains\DocumentManagement\Models\ManagedDocument;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManagedDocumentCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ManagedDocument $managedDocument
    ) {}
}
