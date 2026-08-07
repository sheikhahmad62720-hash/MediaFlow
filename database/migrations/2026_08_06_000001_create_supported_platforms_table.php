<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supported_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->unique()->comment('Canonical host used for URL matching.');
            $table->string('color')->default('#3f6df6')->comment('Brand color used across the UI.');
            $table->string('icon')->nullable()->comment('Icon key resolved by the frontend.');
            $table->string('category', 32)->default('video')->comment('video|music|social|image|direct');
            $table->string('analyzer', 32)->default('direct')->comment('direct|demo analyzer adapter.');
            $table->text('description')->nullable();
            $table->json('formats')->nullable()->comment('Allowed download formats for the platform.');
            $table->unsignedBigInteger('visit_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supported_platforms');
    }
};
