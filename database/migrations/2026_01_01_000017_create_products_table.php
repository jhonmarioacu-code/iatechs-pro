<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('sku')
                ->unique();

            $table->string('barcode')
                ->nullable()
                ->unique();

            $table->string('name');

            $table->text('description')
                ->nullable();

            $table->enum('category', [
                'PART',
                'ACCESSORY',
                'DEVICE',
                'TOOL',
                'CONSUMABLE',
                'OTHER'
            ])->default('PART');

            $table->decimal(
                'cost_price',
                12,
                2
            )->default(0);

            $table->decimal(
                'sale_price',
                12,
                2
            )->default(0);

            $table->integer('stock')
                ->default(0);

            $table->integer('minimum_stock')
                ->default(0);

            $table->string('unit')
                ->default('unit');

            $table->enum('status', [
                'ACTIVE',
                'INACTIVE'
            ])->default('ACTIVE');

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('sku');
            $table->index('barcode');
            $table->index('name');
            $table->index('category');
            $table->index('status');
            $table->index('stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};