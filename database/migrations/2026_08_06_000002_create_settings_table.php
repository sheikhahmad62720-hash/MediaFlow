<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('group', 64)->default('general');
            $table->json('value');
            $table->string('type', 16)->default('string')->comment('string|boolean|integer|json');
            $table->string('label')->nullable();
            $table->boolean('is_public')->default(false)->comment('Exposed through the public settings endpoint.');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
