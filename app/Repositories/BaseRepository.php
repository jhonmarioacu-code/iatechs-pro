<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;

abstract class BaseRepository
{
    use ProvidesRepositoryDefaults;
}
