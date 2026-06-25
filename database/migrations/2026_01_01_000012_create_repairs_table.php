<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('ticket_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('diagnostic_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('quote_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('repair_number')
                ->unique();

            $table->enum('status', [
                'PENDING',
                'ASSIGNED',
                'IN_PROGRESS',
                'WAITING_PARTS',
                'COMPLETED',
                'DELIVERED',
                'CANCELLED'
            ])->default('PENDING');

            $table->longText('repair_notes')
                ->nullable();

            $table->decimal(
                'labor_cost',
                12,
                2
            )->default(0);

            $table->decimal(
                'parts_cost',
                12,
                2
            )->default(0);

            $table->decimal(
                'total_cost',
                12,
                2
            )->default(0);

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamp('delivered_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('branch_id');
            $table->index('ticket_id');
            $table->index('diagnostic_id');
            $table->index('quote_id');
            $table->index('technician_id');
            $table->index('status');
            $table->index('repair_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};