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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\InstructorEvaluation\app\Models\InstructorEvaluation;

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
    protected $appends = ['thumbnail_url', 'all_instructors', 'course_user_progress', 'course_review_score', 'evaluate_instructors'];


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

    /**
     * Get all of the instructorsEvaluation for the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instructorsEvaluation(): HasMany
    {
        return $this->hasMany(InstructorEvaluation::class);
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
            get: fn() => collect([$primary])->merge($partners)->unique('id')->filter()
        );
    }

    protected function evaluateInstructors(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'total_all_instructors' => $this->allInstructors->count(),
                'total_instructor_evaluations' => $this->instructorsEvaluation->count(),
                'is_completed' => $this->instructorsEvaluation->count() === $this->allInstructors->count()
            ]
        );
    }

    public function getAllInstructors()
    {
        $primary = $this->instructor;
        if ($primary == null) {
            return collect();
        }

        $partners = $this->partnerInstructors->pluck('instructor');

        return collect([$primary])->merge($partners)->unique('id')->filter();
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

    /**
     * Get the course reviews score percentage for the authenticated user.
     */
    protected function courseReviewScore(): Attribute
    {
        return Attribute::make(
            get: function () {
                $reviews = $this->reviews;

                $totalScore = $reviews->sum('rating');
                $totalReviews = $reviews->count();

                $averageScore = $totalReviews > 0 ? number_format($totalScore / $totalReviews, 1) : 0;

                return [
                    'average_score' => (string) $averageScore,
                    'total_reviews' => $totalReviews
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

    public static function getTypeOptions(): array
    {
        // Ambil definisi kolom type dari tabel 'courses'
        $type = DB::selectOne("SHOW COLUMNS FROM courses WHERE Field = 'type'");

        // Ambil bagian enum dari kolom (misal: enum('a','b','c'))
        preg_match("/^enum\((.*)\)$/", $type->Type, $matches);

        // Ubah ke array PHP
        $enumValues = [];
        if (isset($matches[1])) {
            foreach (explode(',', $matches[1]) as $value) {
                $v = trim($value, "'");
                $enumValues[$v] = $v === 'course' ? 'Kursus' : Str::title($v); // bisa diganti ucfirst atau custom label
            }
        }

        return $enumValues;
    }
}
