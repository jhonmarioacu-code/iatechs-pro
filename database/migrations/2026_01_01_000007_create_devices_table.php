<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->uuid('uuid')->unique();

            $table->string('device_type');
            $table->string('brand');
            $table->string('model');

            $table->string('serial_number')->nullable();
            $table->string('imei')->nullable();

            $table->string('color')->nullable();

            $table->text('accessories')->nullable();

            $table->text('observations')->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->unique(['company_id', 'serial_number']);
            $table->unique(['company_id', 'imei']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
