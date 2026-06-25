<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {

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

            $table->string('title');

            $table->text('message');

            $table->string('type');

            $table->enum('channel', [
                'IN_APP',
                'EMAIL',
                'SMS',
                'WHATSAPP',
                'PUSH',
                'WEBHOOK'
            ]);

            $table->enum('status', [
                'PENDING',
                'PROCESSING',
                'SENT',
                'DELIVERED',
                'READ',
                'FAILED'
            ])->default('PENDING');

            $table->json('data')
                ->nullable();

            $table->string('recipient')
                ->nullable();

            $table->string('subject')
                ->nullable();

            $table->text('error_message')
                ->nullable();

            $table->timestamp('sent_at')
                ->nullable();

            $table->timestamp('delivered_at')
                ->nullable();

            $table->timestamp('read_at')
                ->nullable();

            $table->timestamps();

            $table->index('company_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('channel');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'notifications'
        );
    }
};