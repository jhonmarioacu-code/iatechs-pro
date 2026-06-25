<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('reference')
                ->nullable();

            $table->enum('type', [
                'IN',
                'OUT',
                'ADJUSTMENT',
                'TRANSFER_IN',
                'TRANSFER_OUT'
            ]);

            $table->integer('quantity');

            $table->integer('stock_before')
                ->default(0);

            $table->integer('stock_after')
                ->default(0);

            $table->text('reason')
                ->nullable();

            $table->json('metadata')
                ->nullable();

            $table->timestamp('movement_date')
                ->useCurrent();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('branch_id');
            $table->index('product_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('movement_date');
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'inventory_movements'
        );
    }
};