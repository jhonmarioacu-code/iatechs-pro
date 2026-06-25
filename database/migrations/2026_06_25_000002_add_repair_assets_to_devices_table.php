<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table): void {
            $table->string('manual_url')->nullable()->after('observations');
            $table->string('diagram_url')->nullable()->after('manual_url');
            $table->string('boardview_url')->nullable()->after('diagram_url');
            $table->boolean('boardview_enabled')->default(false)->after('boardview_url');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table): void {
            $table->dropColumn([
                'manual_url',
                'diagram_url',
                'boardview_url',
                'boardview_enabled',
            ]);
        });
    }
};
