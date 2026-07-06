<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SubKegiatan extends BaseModel { public function kegiatan(): BelongsTo { return $this->belongsTo(Kegiatan::class); } }
