<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUpAction extends Model
{
    use SoftDeletes;

    //fillable
    protected $fillable = [
        'course_id',
        'user_id',
        'summary',
        'file_path',
        'status',
    ];

    //relations
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
