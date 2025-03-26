<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUpAction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'chapter_item_id',
        'instructor_id',
        'chapter_id',
        'course_id',
        'title',
        'description',
        'due_date',
    ];

    function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->withTrashed();
    }
}