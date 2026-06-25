<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_activities', function (Blueprint $table) {

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

            $table->enum('type', [

                'call',
                'email',
                'whatsapp',
                'meeting',
                'follow_up'

            ]);

            $table->string('title');

            $table->text('description')
                ->nullable();

            $table->timestamp('activity_date')
                ->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('lead_id');
            $table->index('opportunity_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
    }
};