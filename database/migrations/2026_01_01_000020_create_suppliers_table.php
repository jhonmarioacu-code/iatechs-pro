<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('legal_name')
                ->nullable();

            $table->string('tax_id')
                ->nullable();

            $table->string('email')
                ->nullable();

            $table->string('phone')
                ->nullable();

            $table->string('website')
                ->nullable();

            $table->string('contact_name')
                ->nullable();

            $table->text('address')
                ->nullable();

            $table->string('city')
                ->nullable();

            $table->string('state')
                ->nullable();

            $table->string('country')
                ->nullable();

            $table->enum('status', [
                'ACTIVE',
                'INACTIVE',
                'BLOCKED'
            ])->default('ACTIVE');

            $table->json('metadata')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('name');
            $table->index('tax_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'suppliers'
        );
    }
};