<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected function transaction(
        callable $callback
    ): mixed {
        return DB::transaction($callback);
    }
}
