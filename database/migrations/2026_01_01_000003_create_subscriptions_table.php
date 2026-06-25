<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('plan_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('billing_cycle')
                ->default('monthly');

            $table->decimal(
                'amount',
                12,
                2
            );

            $table->date('starts_at');

            $table->date('ends_at');

            $table->timestamp('trial_ends_at')
                ->nullable();

            $table->enum('status', [
                'trial',
                'active',
                'past_due',
                'cancelled',
                'expired'
            ])->default('trial');

            $table->string('payment_provider')
                ->nullable();

            $table->string('external_subscription_id')
                ->nullable();

            $table->timestamp('cancelled_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('company_id');
            $table->index('plan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
