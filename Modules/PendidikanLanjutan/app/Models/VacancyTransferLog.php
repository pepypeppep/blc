<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\Database\factories\VacancyTransferLogFactory;

class VacancyTransferLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = ['id'];

    // protected static function newFactory(): VacancyTransferLogFactory
    // {
    //     //return VacancyTransferLogFactory::new();
    // }
}
