<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('social_provider')
                ->nullable()
                ->after('avatar');

            $table->string('social_provider_id')
                ->nullable()
                ->after('social_provider');

            $table->index(['social_provider', 'social_provider_id'], 'users_social_provider_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('users_social_provider_index');
            $table->dropColumn([
                'social_provider',
                'social_provider_id',
            ]);
        });
    }
};
