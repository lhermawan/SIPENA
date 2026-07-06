<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spj_id')->constrained('spjs')->cascadeOnDelete();
            $table->string('nomor_arsip')->unique();
            $table->date('tanggal_arsip');
            $table->string('kategori')->nullable();
            $table->string('lokasi_fisik')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tanggal_arsip', 'kategori']);
        });

        Schema::create('template_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->string('jenis');
            $table->string('file_path')->nullable();
            $table->json('variabel')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('nomor_otomatis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->unsignedInteger('digit')->default(4);
            $table->unsignedBigInteger('nomor_terakhir')->default(0);
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->unsignedTinyInteger('bulan')->nullable();
            $table->boolean('reset_tahunan')->default(true);
            $table->boolean('reset_bulanan')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pengaturan_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('umum');
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_aplikasi');
        Schema::dropIfExists('nomor_otomatis');
        Schema::dropIfExists('template_dokumens');
        Schema::dropIfExists('arsips');
    }
};
