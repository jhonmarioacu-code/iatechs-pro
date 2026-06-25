<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Domains\AIAssistant\Models\AIProvider;

class AIProviderSeeder extends Seeder
{
    public function run(): void
    {
        AIProvider::firstOrCreate(

            [
                'driver' => 'groq'
            ],

            [
                'name' => 'Groq',

                'model' => 'llama-3.3-70b-versatile',

                'enabled' => true,

                'is_default' => true,

                'configuration' => [
                    'temperature' => 0.3,
                    'max_tokens' => 4096
                ]
            ]
        );
    }
}