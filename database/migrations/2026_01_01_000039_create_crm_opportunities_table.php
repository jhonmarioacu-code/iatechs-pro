<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_opportunities', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('lead_id')
                ->constrained('crm_leads')
                ->cascadeOnDelete();

            $table->string('title');

            $table->decimal(
                'amount',
                15,
                2
            )->default(0);

            $table->enum('stage', [

                'prospecting',
                'qualification',
                'proposal',
                'negotiation',
                'won',
                'lost'

            ])->default('prospecting');

            $table->date('expected_close_date')
                ->nullable();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('lead_id');
            $table->index('stage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_opportunities');
    }
};