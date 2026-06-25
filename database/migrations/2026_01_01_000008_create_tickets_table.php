<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('device_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('ticket_number')
                ->unique();

            $table->enum('status', [
                'OPEN',
                'ASSIGNED',
                'IN_DIAGNOSIS',
                'WAITING_QUOTE',
                'APPROVED',
                'IN_REPAIR',
                'READY_DELIVERY',
                'DELIVERED',
                'CLOSED',
                'CANCELLED'
            ])->default('OPEN');

            $table->enum('priority', [
                'LOW',
                'MEDIUM',
                'HIGH',
                'URGENT'
            ])->default('MEDIUM');

            $table->enum('channel', [
                'COUNTER',
                'PHONE',
                'WHATSAPP',
                'EMAIL',
                'WEB'
            ])->default('COUNTER');

            $table->text('reported_problem');

            $table->text('customer_notes')
                ->nullable();

            $table->timestamp('received_at');

            $table->timestamp('assigned_at')
                ->nullable();

            $table->timestamp('closed_at')
                ->nullable();

            $table->boolean('is_warranty')
                ->default(false);

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->index('device_id');
            $table->index('technician_id');
            $table->index('ticket_number');
            $table->index('status');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};