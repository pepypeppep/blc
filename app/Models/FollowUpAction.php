<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'start_date',
        'due_date',
    ];

    function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->withTrashed();
    }

    function chapter(): BelongsTo
    {
        return $this->belongsTo(CourseChapter::class, 'chapter_id', 'id');
    }

    //relationship has one to follow up action response
    function followUpActionResponse(): HasOne
    {
        return $this->hasOne(FollowUpActionResponse::class, 'follow_up_action_id', 'id');
    }
}
