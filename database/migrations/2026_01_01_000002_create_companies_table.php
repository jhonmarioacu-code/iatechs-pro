<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('legal_name')->nullable();
            $table->string('tax_id')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('website')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Colombia');

            $table->string('logo')->nullable();

            $table->enum('status', [
                'active',
                'suspended',
                'cancelled'
            ])->default('active');

            $table->timestamp('trial_ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('uuid');
            $table->index('slug');
            $table->index('status');
            $table->index('email');
            $table->index('tax_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};