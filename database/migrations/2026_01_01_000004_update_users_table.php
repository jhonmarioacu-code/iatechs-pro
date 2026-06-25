<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->foreignId('company_id')
                ->nullable()
                ->after('id')
                ->constrained('companies')
                ->nullOnDelete();

            $table->uuid('uuid')
                ->unique()
                ->after('company_id');

            $table->string('phone')
                ->nullable();

            $table->string('avatar')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamp('last_login_at')
                ->nullable();

            $table->timestamp('email_verified_at')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropConstrainedForeignId(
                'company_id'
            );

            $table->dropColumn([
                'uuid',
                'phone',
                'avatar',
                'is_active',
                'last_login_at',
            ]);
        });
    }
};