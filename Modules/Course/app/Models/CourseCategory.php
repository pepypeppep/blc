<?php

namespace Modules\Course\app\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slug',
        'icon',
        'status',
        'order',
        'parent_id',
    ];
    protected $appends = ['thumbnail_url'];

    function scopeActive()
    {
        return $this->where(['status' => 1]);
    }
    public function getNameAttribute(): ?string
    {
        return $this->translation->name ?? $this->translations->first()->name;
    }

    public function getTranslation($code): ?CourseCategoryTranslation
    {
        return $this->hasOne(CourseCategoryTranslation::class)->where('lang_code', $code)->first();
    }
    public function translation(): ?HasOne
    {
        return $this->hasOne(CourseCategoryTranslation::class)->where('lang_code', getSessionLanguage());
    }
    public function translations(): ?HasMany
    {
        return $this->hasMany(CourseCategoryTranslation::class, 'course_category_id');
    }

    public function subCategories()
    {
        return $this->hasMany(CourseCategory::class, 'parent_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(CourseCategory::class, 'parent_id');
    }

    function filters(): HasMany
    {
        return $this->hasMany(CourseFilter::class, 'course_category_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    public function allCourses()
    {
        $subCategories = $this->subCategories()->with('courses')->get();
        $courses = $this->courses()->get();

        foreach ($subCategories as $subCategory) {
            $courses = $courses->merge($subCategory->courses);
        }

        return $courses;
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attributes['icon'] ? route('admin.course-category.thumbnail', ['id' => $this->attributes['id']]) : null
        );
    }
}
