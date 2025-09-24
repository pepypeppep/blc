<?php

namespace Modules\InstructorEvaluation\app\Models;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorEvaluation extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'course_id',
    //     'student_id',
    //     'course_chapter_id',
    //     'instructor_id',
    //     'rating',
    //     'feedback',
    // ];
    protected $guarded = ['id'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
