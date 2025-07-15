<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnorJenis extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the unors for the UnorJenis
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unors(): HasMany
    {
        return $this->hasMany(Unor::class);
    }
}
