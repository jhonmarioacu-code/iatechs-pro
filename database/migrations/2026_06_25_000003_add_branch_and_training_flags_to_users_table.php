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
            $table->foreignId('branch_id')
                ->nullable()
                ->after('company_id')
                ->constrained('branches')
                ->nullOnDelete();

            $table->boolean('technician_course_enabled')
                ->default(false)
                ->after('is_active');

            $table->boolean('technician_exam_enabled')
                ->default(false)
                ->after('technician_course_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('branch_id');
            $table->dropColumn([
                'technician_course_enabled',
                'technician_exam_enabled',
            ]);
        });
    }
};
