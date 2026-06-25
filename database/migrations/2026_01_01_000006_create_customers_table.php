<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (
            Blueprint $table
        ) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Tenant
            |--------------------------------------------------------------------------
            */

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Identification
            |--------------------------------------------------------------------------
            */

            $table->uuid('uuid')
                ->unique();

            $table->string('customer_code')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Customer Type
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'customer_type',
                [
                    'person',
                    'company'
                ]
            )->default('person');

            /*
            |--------------------------------------------------------------------------
            | Personal Information
            |--------------------------------------------------------------------------
            */

            $table->string('first_name');

            $table->string('last_name')
                ->nullable();

            $table->string('company_name')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Legal Information
            |--------------------------------------------------------------------------
            */

            $table->string('document_type')
                ->nullable();

            $table->string('document_number')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            $table->string('email')
                ->nullable();

            $table->string('phone')
                ->nullable();

            $table->string('mobile')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            $table->text('address')
                ->nullable();

            $table->string('city')
                ->nullable();

            $table->string('state')
                ->nullable();

            $table->string('country')
                ->default('Colombia');

            /*
            |--------------------------------------------------------------------------
            | CRM / ERP
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'credit_limit',
                12,
                2
            )->default(0);

            $table->decimal(
                'balance',
                12,
                2
            )->default(0);

            $table->date('customer_since')
                ->nullable();

            $table->boolean(
                'accepts_marketing'
            )->default(false);

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Audit
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

            $table->index('customer_code');

            $table->index('document_number');

            $table->index('email');

            $table->index([
                'company_id',
                'document_number'
            ]);

            $table->index([
                'company_id',
                'email'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'customers'
        );
    }
};