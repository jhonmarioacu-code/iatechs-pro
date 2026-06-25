<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {

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

            $table->string('quote_number')
                ->unique();

            $table->enum('status', [
                'DRAFT',
                'PENDING_APPROVAL',
                'APPROVED',
                'REJECTED',
                'EXPIRED',
                'CANCELLED'
            ])->default('DRAFT');

            $table->decimal(
                'subtotal',
                12,
                2
            )->default(0);

            $table->decimal(
                'tax',
                12,
                2
            )->default(0);

            $table->decimal(
                'discount',
                12,
                2
            )->default(0);

            $table->decimal(
                'total',
                12,
                2
            )->default(0);

            $table->text('notes')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('branch_id');
            $table->index('ticket_id');
            $table->index('diagnostic_id');
            $table->index('status');
            $table->index('quote_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};