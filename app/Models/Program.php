<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Program extends BaseModel { public function kegiatans(): HasMany { return $this->hasMany(Kegiatan::class); } }
