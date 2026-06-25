<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('order_number')
                ->unique();

            $table->enum('status', [
                'DRAFT',
                'PENDING',
                'APPROVED',
                'PARTIALLY_RECEIVED',
                'RECEIVED',
                'CANCELLED'
            ])->default('DRAFT');

            $table->decimal('subtotal', 15, 2)
                ->default(0);

            $table->decimal('tax', 15, 2)
                ->default(0);

            $table->decimal('discount', 15, 2)
                ->default(0);

            $table->decimal('total', 15, 2)
                ->default(0);

            $table->text('notes')
                ->nullable();

            $table->timestamp('ordered_at')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('received_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('supplier_id');
            $table->index('status');
            $table->index('order_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'purchase_orders'
        );
    }
};