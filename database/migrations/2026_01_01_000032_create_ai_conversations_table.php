<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_conversations', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title')
                ->nullable();

            $table->string('provider')
                ->default('groq');

            $table->string('model')
                ->nullable();

            $table->json('context')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index('company_id');
            $table->index('user_id');
            $table->index('provider');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'ai_conversations'
        );
    }
};