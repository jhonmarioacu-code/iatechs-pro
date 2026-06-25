<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_leads', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('email')
                ->nullable();

            $table->string('phone')
                ->nullable();

            $table->enum('source', [

                'website',
                'facebook',
                'instagram',
                'whatsapp',
                'google',
                'referral',
                'manual'

            ])->default('manual');

            $table->enum('status', [

                'new',
                'contacted',
                'qualified',
                'converted',
                'lost'

            ])->default('new');

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('company_id');
            $table->index('status');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_leads');
    }
};