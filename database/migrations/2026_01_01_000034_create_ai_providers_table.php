<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_providers', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('driver');

            $table->string('model');

            $table->boolean('enabled')
                ->default(true);

            $table->boolean('is_default')
                ->default(false);

            $table->json('configuration')
                ->nullable();

            $table->timestamps();

            $table->index('driver');
            $table->index('enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'ai_providers'
        );
    }
};