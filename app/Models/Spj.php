<?php

namespace App\Models;

use App\Enums\SpjStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Spj extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $casts = ['tanggal' => 'date', 'status' => SpjStatus::class, 'total_belanja' => 'decimal:2'];

    public function bidang(): BelongsTo { return $this->belongsTo(Bidang::class); }
    public function program(): BelongsTo { return $this->belongsTo(Program::class); }
    public function kegiatan(): BelongsTo { return $this->belongsTo(Kegiatan::class); }
    public function subKegiatan(): BelongsTo { return $this->belongsTo(SubKegiatan::class); }
    public function rekeningBelanja(): BelongsTo { return $this->belongsTo(RekeningBelanja::class); }
    public function pptk(): BelongsTo { return $this->belongsTo(Penandatangan::class, 'pptk_id'); }
    public function ppk(): BelongsTo { return $this->belongsTo(Penandatangan::class, 'ppk_id'); }
    public function bendahara(): BelongsTo { return $this->belongsTo(Penandatangan::class, 'bendahara_id'); }
    public function paKpa(): BelongsTo { return $this->belongsTo(Penandatangan::class, 'pa_kpa_id'); }
    public function items(): HasMany { return $this->hasMany(SpjItem::class); }
    public function buktiTransaksis(): HasMany { return $this->hasMany(BuktiTransaksi::class); }
}
