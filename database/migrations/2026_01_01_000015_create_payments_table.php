<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | UUID
            |--------------------------------------------------------------------------
            */

            $table->uuid('uuid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Tenant
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            $table->foreignId('invoice_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Payment Info
            |--------------------------------------------------------------------------
            */

            $table->string('payment_number')
                ->unique();

            $table->enum('payment_method', [

                'CASH',

                'CARD',

                'BANK_TRANSFER',

                'PSE',

                'NEQUI',

                'DAVIPLATA',

                'PAYPAL',

                'STRIPE',

                'MERCADOPAGO',

                'OTHER'

            ]);

            $table->string(
                'external_transaction_id'
            )->nullable();

            $table->string(
                'reference'
            )->nullable();

            $table->string(
                'currency',
                10
            )->default('COP');

            /*
            |--------------------------------------------------------------------------
            | Amounts
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'amount',
                12,
                2
            );

            $table->boolean(
                'is_partial'
            )->default(false);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [

                'PENDING',

                'COMPLETED',

                'FAILED',

                'REFUNDED',

                'CANCELLED'

            ])->default('PENDING');

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp(
                'paid_at'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $table->text(
                'notes'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('company_id');
            $table->index('branch_id');

            $table->index('invoice_id');
            $table->index('customer_id');

            $table->index('processed_by');

            $table->index('payment_number');

            $table->index('payment_method');

            $table->index('status');

            $table->index('paid_at');

            $table->index(
                'external_transaction_id'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'payments'
        );
    }
};
