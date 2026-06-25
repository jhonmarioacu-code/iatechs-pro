<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranties', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

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
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('repair_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('warranty_number')
                ->unique();

            $table->enum('type', [
                'REPAIR',
                'PARTS',
                'PRODUCT',
                'EXTENDED'
            ])->default('REPAIR');

            $table->enum('status', [
                'ACTIVE',
                'EXPIRED',
                'VOID',
                'CLAIMED'
            ])->default('ACTIVE');

            $table->date('start_date');

            $table->date('end_date');

            $table->text('terms')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->index('device_id');
            $table->index('repair_id');
            $table->index('invoice_id');
            $table->index('warranty_number');
            $table->index('status');
            $table->index('type');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};