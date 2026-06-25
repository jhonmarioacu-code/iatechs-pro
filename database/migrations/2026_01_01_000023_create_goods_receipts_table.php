<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('purchase_order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('received_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('receipt_number')
                ->unique();

            $table->enum('status', [
                'DRAFT',
                'RECEIVED',
                'PARTIAL',
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

            $table->timestamp('received_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('purchase_order_id');
            $table->index('supplier_id');
            $table->index('status');
            $table->index('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'goods_receipts'
        );
    }
};