<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');

            $table->enum('type', [

                'sales',

                'invoices',

                'payments',

                'tickets',

                'diagnostics',

                'repairs',

                'inventory',

                'purchase_orders',

                'goods_receipts',

                'warranties',

                'customers',

                'technicians',

                'companies'
            ]);

            $table->json('filters')
                ->nullable();

            $table->unsignedInteger('total_records')
                ->default(0);

            $table->enum('status', [

                'PENDING',

                'PROCESSING',

                'COMPLETED',

                'FAILED'
            ])->default('PENDING');

            $table->timestamp('generated_at')
                ->nullable();

            $table->timestamps();

            $table->index('company_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'reports'
        );
    }
};