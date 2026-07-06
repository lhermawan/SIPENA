<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penandatangan extends BaseModel
{
    protected $casts = ['is_active' => 'boolean'];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}
