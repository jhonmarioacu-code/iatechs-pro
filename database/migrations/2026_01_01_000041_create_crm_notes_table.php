<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_notes', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('lead_id')
                ->nullable()
                ->constrained('crm_leads')
                ->cascadeOnDelete();

            $table->foreignId('opportunity_id')
                ->nullable()
                ->constrained('crm_opportunities')
                ->cascadeOnDelete();

            $table->longText('content');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('lead_id');
            $table->index('opportunity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_notes');
    }
};