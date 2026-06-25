<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_exports', function (Blueprint $table) {

            $table->id();

            $table->foreignId('report_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('format', [

                'PDF',

                'XLSX',

                'CSV',

                'JSON'
            ]);

            $table->string('file_name');

            $table->string('file_path');

            $table->unsignedBigInteger('file_size')
                ->nullable();

            $table->timestamp('generated_at')
                ->nullable();

            $table->timestamps();

            $table->index('report_id');
            $table->index('generated_by');
            $table->index('format');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'report_exports'
        );
    }
};