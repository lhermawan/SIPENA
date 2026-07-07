<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            if (! Schema::hasColumn('spjs', 'bidang_id')) {
                $table->foreignId('bidang_id')->nullable()->after('tanggal')->constrained('bidangs')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            if (Schema::hasColumn('spjs', 'bidang_id')) {
                $table->dropConstrainedForeignId('bidang_id');
            }
        });
    }
};
