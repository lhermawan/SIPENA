<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) { $table->id(); $table->string('nama'); $table->string('nip')->nullable()->unique(); $table->string('pangkat')->nullable(); $table->string('golongan')->nullable(); $table->string('jabatan')->nullable(); $table->string('unit_kerja')->nullable(); $table->boolean('is_active')->default(true); $table->timestamps(); });
        Schema::create('penandatangans', function (Blueprint $table) { $table->id(); $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete(); $table->string('nama'); $table->string('nip')->nullable(); $table->string('jabatan'); $table->string('peran'); $table->boolean('is_active')->default(true); $table->timestamps(); });
        Schema::create('rekanans', function (Blueprint $table) { $table->id(); $table->string('nama'); $table->string('npwp')->nullable(); $table->text('alamat')->nullable(); $table->string('nomor_rekening')->nullable(); $table->string('nama_bank')->nullable(); $table->string('kontak')->nullable(); $table->timestamps(); });
        Schema::create('programs', function (Blueprint $table) { $table->id(); $table->string('kode')->unique(); $table->string('nama'); $table->timestamps(); });
        Schema::create('kegiatans', function (Blueprint $table) { $table->id(); $table->foreignId('program_id')->constrained()->cascadeOnDelete(); $table->string('kode'); $table->string('nama'); $table->timestamps(); $table->unique(['program_id','kode']); });
        Schema::create('sub_kegiatans', function (Blueprint $table) { $table->id(); $table->foreignId('kegiatan_id')->constrained()->cascadeOnDelete(); $table->string('kode'); $table->string('nama'); $table->timestamps(); $table->unique(['kegiatan_id','kode']); });
        Schema::create('rekening_belanjas', function (Blueprint $table) { $table->id(); $table->string('kode')->unique(); $table->string('nama'); $table->timestamps(); });
        Schema::create('satuans', function (Blueprint $table) { $table->id(); $table->string('nama')->unique(); $table->timestamps(); });
    }

    public function down(): void
    {
        Schema::dropIfExists('satuans'); Schema::dropIfExists('rekening_belanjas'); Schema::dropIfExists('sub_kegiatans'); Schema::dropIfExists('kegiatans'); Schema::dropIfExists('programs'); Schema::dropIfExists('rekanans'); Schema::dropIfExists('penandatangans'); Schema::dropIfExists('pegawais');
    }
};
