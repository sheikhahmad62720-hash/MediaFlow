<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('platform_id')->nullable()->constrained('supported_platforms')->nullOnDelete();
            $table->text('source_url');
            $table->string('title')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->unsignedBigInteger('duration')->nullable()->comment('Duration in seconds.');
            $table->string('resolution', 32)->nullable()->comment('e.g. 1080p, 4K.');
            $table->unsignedBigInteger('file_size')->nullable()->comment('Size in bytes.');
            $table->string('media_type', 16)->nullable()->comment('video|audio|image');
            $table->string('format', 16)->nullable()->comment('mp4|webm|mp3|m4a|jpg');
            $table->string('quality', 32)->nullable();
            $table->string('status', 16)->default('queued')->comment('queued|processing|completed|failed');
            $table->string('file_path')->nullable()->comment('Path relative to the download disk.');
            $table->string('file_name')->nullable();
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['platform_id', 'created_at']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
