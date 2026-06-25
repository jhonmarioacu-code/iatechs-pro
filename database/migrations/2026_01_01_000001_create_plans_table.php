<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('name');

            $table->string('slug')
                ->unique();

            $table->text('description')
                ->nullable();

            $table->decimal(
                'monthly_price',
                12,
                2
            )->default(0);

            $table->decimal(
                'yearly_price',
                12,
                2
            )->default(0);

            $table->integer('max_users')
                ->default(1);

            $table->integer('max_branches')
                ->default(1);

            $table->integer('max_storage_gb')
                ->default(1);

            $table->integer('max_tickets')
                ->default(100);

            $table->integer('ai_requests_limit')
                ->default(0);

            $table->integer('trial_days')
                ->default(0);

            $table->boolean('has_ai')
                ->default(false);

            $table->boolean('has_inventory')
                ->default(false);

            $table->boolean('has_reports')
                ->default(false);

            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            $table->timestamps();

            $table->softDeletes();

            $table->index('slug');

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};