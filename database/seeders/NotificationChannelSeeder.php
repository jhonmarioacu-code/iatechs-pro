<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Companies\Models\Company;
use App\Domains\Notifications\Models\NotificationChannel;
use Illuminate\Database\Seeder;

class NotificationChannelSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('slug', 'iatechs-demo')->first();

        if (!$company) {
            return;
        }

        NotificationChannel::updateOrCreate(
            [
                'company_id' => $company->id,
                'channel' => 'EMAIL',
            ],
            [
                'enabled' => true,
                'configuration' => [
                    'mailer' => 'log',
                ],
                'description' => 'Default local email channel',
                'is_working' => true,
            ]
        );
    }
}
