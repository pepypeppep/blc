<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $fillable = [
        'id',
        'esurat_id',
        'unor_id',
        'name',
    ];
}
