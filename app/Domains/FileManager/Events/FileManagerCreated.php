<?php

declare(strict_types=1);

namespace App\Domains\FileManager\Events;

use App\Domains\FileManager\Models\FileManager;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileManagerCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly FileManager $fileManager
    ) {}
}
