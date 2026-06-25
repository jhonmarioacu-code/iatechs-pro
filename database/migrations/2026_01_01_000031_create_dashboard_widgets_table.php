<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_widgets', function (Blueprint $table) {

            $table->id();

            $table->foreignId('dashboard_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('widget');

            $table->integer('position')
                ->default(1);

            $table->integer('width')
                ->default(6);

            $table->integer('height')
                ->default(1);

            $table->json('settings')
                ->nullable();

            $table->boolean('enabled')
                ->default(true);

            $table->timestamps();

            $table->index('dashboard_id');
            $table->index('widget');
            $table->index('enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'dashboard_widgets'
        );
    }
};