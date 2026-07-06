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

    public function program(): BelongsTo { return $this->belongsTo(Program::class); }
    public function kegiatan(): BelongsTo { return $this->belongsTo(Kegiatan::class); }
    public function subKegiatan(): BelongsTo { return $this->belongsTo(SubKegiatan::class); }
    public function rekeningBelanja(): BelongsTo { return $this->belongsTo(RekeningBelanja::class); }
    public function items(): HasMany { return $this->hasMany(SpjItem::class); }
    public function buktiTransaksis(): HasMany { return $this->hasMany(BuktiTransaksi::class); }
}
