<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SpjItem extends BaseModel { protected $casts = ['volume'=>'decimal:2','harga_satuan'=>'decimal:2','total'=>'decimal:2']; public function spj(): BelongsTo { return $this->belongsTo(Spj::class); } public function satuan(): BelongsTo { return $this->belongsTo(Satuan::class); } }
