<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('from_branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->foreignId('to_branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->foreignId('requested_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('transfer_number')
                ->unique();

            $table->integer('quantity');

            $table->enum('status', [
                'PENDING',
                'IN_TRANSIT',
                'COMPLETED',
                'CANCELLED'
            ])->default('PENDING');

            $table->text('notes')
                ->nullable();

            $table->timestamp('requested_at')
                ->useCurrent();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('product_id');
            $table->index('from_branch_id');
            $table->index('to_branch_id');
            $table->index('status');
            $table->index('transfer_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'stock_transfers'
        );
    }
};