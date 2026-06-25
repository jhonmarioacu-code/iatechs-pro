<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('event');

            $table->string('entity_type');

            $table->unsignedBigInteger('entity_id')
                ->nullable();

            $table->json('old_values')
                ->nullable();

            $table->json('new_values')
                ->nullable();

            $table->ipAddress('ip_address')
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            $table->string('url')
                ->nullable();

            $table->string('method')
                ->nullable();

            $table->string('module')
                ->nullable();

            $table->timestamp('occurred_at')
                ->nullable();

            $table->timestamps();

            $table->index('company_id');
            $table->index('user_id');
            $table->index('event');
            $table->index('entity_type');
            $table->index('entity_id');
            $table->index('module');
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'audit_logs'
        );
    }
};