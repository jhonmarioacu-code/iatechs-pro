<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnostics', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('ticket_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('diagnostic_code')
                ->unique();

            $table->enum('status', [
                'PENDING',
                'IN_PROGRESS',
                'COMPLETED',
                'REQUIRES_PARTS',
                'NOT_REPAIRABLE',
                'CANCELLED'
            ])->default('PENDING');

            $table->text('reported_problem');

            $table->longText('diagnostic_result')
                ->nullable();

            $table->longText('recommended_solution')
                ->nullable();

            $table->decimal(
                'estimated_cost',
                12,
                2
            )->default(0);

            $table->integer(
                'estimated_hours'
            )->default(0);

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('finished_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('branch_id');
            $table->index('ticket_id');
            $table->index('technician_id');
            $table->index('status');
            $table->index('diagnostic_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostics');
    }
};