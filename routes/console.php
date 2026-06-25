<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('iatechs:health', function () {
    $this->info('IAtechs Pro console is ready.');
});
