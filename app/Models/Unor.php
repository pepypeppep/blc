<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unor extends Model
{
    protected $fillable = [
        'id',
        'parent_id',
        'is_instansi',
        'name',
        'created_at',
        'updated_at',
    ];
}
