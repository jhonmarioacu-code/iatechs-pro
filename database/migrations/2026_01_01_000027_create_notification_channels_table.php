<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_channels', function (Blueprint $table) {

            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('channel', [

                'EMAIL',

                'SMS',

                'WHATSAPP',

                'PUSH',

                'WEBHOOK'
            ]);

            $table->boolean('enabled')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Configuración del canal
            |--------------------------------------------------------------------------
            |
            | EMAIL:
            | host, port, username, password, encryption
            |
            | WHATSAPP:
            | api_url, token, phone_number_id
            |
            | SMS:
            | provider, api_key, api_secret
            |
            | PUSH:
            | firebase_credentials
            |
            | WEBHOOK:
            | endpoint, token
            |
            */

            $table->json('configuration')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->timestamp('last_tested_at')
                ->nullable();

            $table->boolean('is_working')
                ->default(true);

            $table->timestamps();

            $table->unique([
                'company_id',
                'channel'
            ]);

            $table->index('company_id');
            $table->index('channel');
            $table->index('enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'notification_channels'
        );
    }
};