<?php

namespace Modules\Course\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Database\factories\CourseTosFactory;

class CourseTos extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): CourseTosFactory
    // {
    //     //return CourseTosFactory::new();
    // }

    protected $guarded = ['id'];
}
