<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo; use Spatie\MediaLibrary\HasMedia; use Spatie\MediaLibrary\InteractsWithMedia;
class BuktiTransaksi extends BaseModel implements HasMedia { use InteractsWithMedia; protected $casts=['tanggal'=>'date','nominal'=>'decimal:2']; public function spj(): BelongsTo { return $this->belongsTo(Spj::class); } public function rekanan(): BelongsTo { return $this->belongsTo(Rekanan::class); } }
