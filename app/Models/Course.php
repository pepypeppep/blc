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
    protected $appends = ['thumbnail_url', 'all_instructors', 'course_user_progress'];


    public const CLASS_KLASIKAL_DARING = 'klasikal_daring';
    public const CLASS_KLASIKAL_LURING = 'klasikal_luring';
    public const CLASS_NON_KLASIKAL = 'non_klasikal';

    public const STATUS_IS_DRAFT = 'is_draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const ISAPPROVED_APPROVED = 'approved';
    public const ISAPPROVED_PENDING = 'pending';
    public const ISAPPROVED_REJECTED = 'rejected';

    function scopeActive()
    {
        return $this->where(['is_approved' => 'approved', 'status' => 'active']);
    }
    public function getFavoriteByClientAttribute()
    {
        if (auth()->guard('web')->check()) {
            return in_array(userAuth()->id, $this->favoriteBy->pluck('id')->toArray());
            // return $this->relationLoaded('favoriteBy') ? in_array(userAuth()->id, $this->favoriteBy->pluck('id')->toArray()) : false;
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
        return $this->belongsTo(User::class, 'instructor_id', 'id');
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

    public function progress()
    {
        return $this->hasMany(CourseProgress::class);
    }

    function iscompleted(): bool
    {
        $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) {
            $q->where('course_id', $this->id);
        })->count();

        $courseLectureCompletedByUser = CourseProgress::where('user_id', userAuth()->id)
            ->where('course_id', $this->id)->where('watched', 1)->count();

        $courseCompletedPercent = $courseLectureCount > 0 ? ($courseLectureCompletedByUser / $courseLectureCount) * 100 : 0;

        return $courseCompletedPercent == 100;
    }

    protected function allInstructors(): Attribute
    {
        $primary = $this->instructor;
        $partners = $this->partnerInstructors->pluck('instructor');
        return Attribute::make(
            get: fn() => collect([$primary])->merge($partners)->filter()
        );
    }

    public function getAllInstructors()
    {
        $primary = $this->instructor;
        if ($primary == null) {
            return collect();
        }

        $partners = $this->partnerInstructors->pluck('instructor');

        return collect([$primary])->merge($partners);
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attributes['thumbnail'] ? route('api.courses.get-thumbnail', ['courseId' => $this->attributes['id']]) : null
        );
    }

    /**
     * Get the course progress percentage for the authenticated user.
     */
    protected function courseUserProgress(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Return null if no user is authenticated
                if (!auth()->check()) {
                    return null;
                }

                $userId = auth()->user()->id;

                // Count total lectures in the course
                $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) {
                    $q->where('course_id', $this->id);
                })->count();

                // Count completed lectures by user
                $courseLectureCompletedByUser = CourseProgress::where('user_id', $userId)
                    ->where('course_id', $this->id)
                    ->where('watched', 1)
                    ->count();

                // Calculate percentage
                $courseCompletedPercent = $courseLectureCount > 0
                    ? ($courseLectureCompletedByUser / $courseLectureCount) * 100
                    : 0;

                return [
                    'total_lectures' => $courseLectureCount,
                    'completed_lectures' => $courseLectureCompletedByUser,
                    'percentage' => number_format($courseCompletedPercent, 1),
                    'is_completed' => $courseCompletedPercent == 100,
                ];
            }
        );
    }

    public function getCourseUserProgressApi($userId = null)
    {
        // If no userId provided and no authenticated user, return null
        if (!$userId) {
            return null;
        }

        $userId = $userId ?? auth()->id();

        // Count total lectures in the course
        $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) {
            $q->where('course_id', $this->id);
        })->count();

        // Count completed lectures by user
        $courseLectureCompletedByUser = CourseProgress::where('user_id', $userId)
            ->where('course_id', $this->id)
            ->where('watched', 1)
            ->count();

        // Calculate percentage
        $courseCompletedPercent = $courseLectureCount > 0
            ? ($courseLectureCompletedByUser / $courseLectureCount) * 100
            : 0;

        return [
            'total_lectures' => $courseLectureCount,
            'completed_lectures' => $courseLectureCompletedByUser,
            'percentage' => number_format($courseCompletedPercent, 1),
            'is_completed' => $courseCompletedPercent == 100,
        ];
    }

    public function signers()
    {
        return $this->hasMany(CourseSigner::class);
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
