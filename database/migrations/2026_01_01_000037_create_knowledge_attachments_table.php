<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_attachments', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->foreignId('article_id')
                ->constrained('knowledge_articles')
                ->cascadeOnDelete();

            $table->string('file_name');

            $table->string('original_name');

            $table->string('mime_type')
                ->nullable();

            $table->unsignedBigInteger('file_size')
                ->default(0);

            $table->string('disk')
                ->default('public');

            $table->string('path');

            $table->timestamps();

            $table->index('article_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'knowledge_attachments'
        );
    }
};