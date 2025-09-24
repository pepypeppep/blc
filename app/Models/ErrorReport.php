<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErrorReport extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the user relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship for Course module
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'module_id');
    }

    /**
     * Relationship for Lesson module
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseChapterLesson::class, 'module_id');
    }

    /**
     * Dynamic accessor to get the appropriate relationship model
     */
    public function getModuleModelAttribute()
    {
        switch ($this->module) {
            case 'Course':
                return $this->course;
            case 'Lesson':
                return $this->lesson;
            default:
                return null;
        }
    }

    /**
     * Extract lesson ID from title using regex
     */
    public function getExtractedLessonIdAttribute()
    {
        // Pattern 1: {lessonId: 2400} format
        preg_match('/{lessonId:\s*(\d+)}/i', $this->title, $pattern1Matches);
        if (isset($pattern1Matches[1])) {
            return (int)$pattern1Matches[1];
        }

        // Pattern 2: Just a number at the end "Video Youtube 1961 Error"
        preg_match('/(\d+)\s*Error$/i', $this->title, $pattern2Matches);
        if (isset($pattern2Matches[1])) {
            return (int)$pattern2Matches[1];
        }

        // Pattern 3: Any standalone number in the title
        preg_match('/\b(\d+)\b/', $this->title, $pattern3Matches);
        if (isset($pattern3Matches[1])) {
            return (int)$pattern3Matches[1];
        }

        return null;
    }

    /**
     * Get the Lesson model from the extracted ID in title
     */
    public function getLessonModelAttribute()
    {
        $lessonId = $this->extracted_lesson_id;

        if ($lessonId) {
            return CourseChapterLesson::find($lessonId);
        }

        return null;
    }

    /**
     * Check if this error report has a lesson reference in title
     */
    public function getHasLessonInTitleAttribute()
    {
        return !is_null($this->extracted_lesson_id);
    }

    /**
     * Eager load all possible module relationships
     */
    public function scopeWithModule($query)
    {
        return $query->with(['course', 'lesson']);
    }

    /**
     * Scope to find reports with lesson references in title
     */
    public function scopeWithLessonInTitle($query)
    {
        return $query->where('title', 'like', '%{lessonId:%}%');
    }
}
