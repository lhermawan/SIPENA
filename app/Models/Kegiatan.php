<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo; use Illuminate\Database\Eloquent\Relations\HasMany;
class Kegiatan extends BaseModel { public function program(): BelongsTo { return $this->belongsTo(Program::class); } public function subKegiatans(): HasMany { return $this->hasMany(SubKegiatan::class); } }
