<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Order\app\Models\Enrollment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\app\Models\CourseCategory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 * @package App\Models
 * 
 * @property int $jp
 */
class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['thumbnail_url'];

    function scopeActive()
    {
        return $this->where(['is_approved' => 'approved', 'status' => 'active']);
    }
    public function getFavoriteByClientAttribute()
    {
        if (auth()->guard('web')->check()) {
            return $this->relationLoaded('favoriteBy') ? in_array(userAuth()->id, $this->favoriteBy->pluck('id')->toArray()) : false;
        }

        return false;
    }

    public function favoriteBy()
    {
        return $this->belongsToMany(User::class, 'favorite_course_user')->withTimestamps();
    }

    function partnerInstructors(): HasMany
    {
        return $this->hasMany(CoursePartnerInstructor::class, 'course_id', 'id');
    }

    function levels(): HasMany
    {
        return $this->hasMany(CourseSelectedLevel::class, 'course_id', 'id');
    }
    function languages(): HasMany
    {
        return $this->hasMany(CourseSelectedLanguage::class, 'course_id', 'id');
    }

    function filtersOptions(): HasMany
    {
        return $this->hasMany(CourseSelectedFilterOption::class, 'course_id', 'id');
    }

    function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id', 'id')->withDefault();
    }

    function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id', 'id')->withDefault();
    }

    function chapters(): HasMany
    {
        return $this->hasMany(CourseChapter::class, 'course_id', 'id');
    }

    function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class, 'course_id', 'id');
    }
    function lessons(): HasMany
    {
        return $this->hasMany(CourseChapterLesson::class, 'course_id', 'id');
    }

    function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id', 'id');
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attributes['thumbnail'] ? route('api.courses.get-thumbnail', ['courseId' => $this->attributes['id']]) : null
        );
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($course) {
            // Delete related chapters
            $course->chapters()->each(function ($chapter) {
                $chapter->delete();
            });

            // Delete related partner instructors
            $course->partnerInstructors()->each(function ($instructor) {
                $instructor->delete();
            });

            // Delete related levels
            $course->levels()->each(function ($level) {
                $level->delete();
            });

            // Delete related languages
            $course->languages()->each(function ($language) {
                $language->delete();
            });

            // Delete related filter options
            $course->filtersOptions()->each(function ($filterOption) {
                $filterOption->delete();
            });

            // Delete related reviews
            $course->reviews()->each(function ($review) {
                $review->delete();
            });
        });
    }
}
