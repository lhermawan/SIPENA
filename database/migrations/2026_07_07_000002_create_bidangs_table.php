<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bidang_user', function (Blueprint $table) {
            $table->foreignId('bidang_id')->constrained('bidangs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['bidang_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidang_user');
        Schema::dropIfExists('bidangs');
    }
};
