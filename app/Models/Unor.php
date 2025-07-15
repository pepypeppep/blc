<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unor extends Model
{
    protected $fillable = [
        'id',
        'unor_jenis_id',
        'parent_id',
        'is_instansi',
        'name',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the jenis that owns the Unor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis(): BelongsTo
    {
        return $this->belongsTo(UnorJenis::class);
    }
}
