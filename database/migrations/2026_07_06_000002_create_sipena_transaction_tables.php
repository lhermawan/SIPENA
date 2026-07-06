<?php

use App\Enums\SpjStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spjs', function (Blueprint $table) { $table->id(); $table->string('nomor_spj')->unique(); $table->date('tanggal'); $table->foreignId('program_id')->constrained(); $table->foreignId('kegiatan_id')->constrained(); $table->foreignId('sub_kegiatan_id')->constrained(); $table->foreignId('rekening_belanja_id')->constrained(); $table->foreignId('pptk_id')->nullable()->constrained('penandatangans')->nullOnDelete(); $table->foreignId('ppk_id')->nullable()->constrained('penandatangans')->nullOnDelete(); $table->foreignId('bendahara_id')->nullable()->constrained('penandatangans')->nullOnDelete(); $table->foreignId('pa_kpa_id')->nullable()->constrained('penandatangans')->nullOnDelete(); $table->string('status')->default(SpjStatus::Draft->value); $table->decimal('total_belanja', 18, 2)->default(0); $table->text('terbilang')->nullable(); $table->timestamp('finalized_at')->nullable(); $table->timestamps(); });
        Schema::create('spj_items', function (Blueprint $table) { $table->id(); $table->foreignId('spj_id')->constrained('spjs')->cascadeOnDelete(); $table->text('uraian'); $table->decimal('volume', 12, 2); $table->foreignId('satuan_id')->constrained(); $table->decimal('harga_satuan', 18, 2); $table->decimal('total', 18, 2); $table->timestamps(); });
        Schema::create('bukti_transaksis', function (Blueprint $table) { $table->id(); $table->foreignId('spj_id')->constrained('spjs')->cascadeOnDelete(); $table->foreignId('rekanan_id')->nullable()->constrained('rekanans')->nullOnDelete(); $table->string('jenis'); $table->string('nomor')->nullable(); $table->date('tanggal')->nullable(); $table->decimal('nominal', 18, 2)->default(0); $table->text('keterangan')->nullable(); $table->timestamps(); });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_transaksis'); Schema::dropIfExists('spj_items'); Schema::dropIfExists('spjs');
    }
};
