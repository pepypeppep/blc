<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CourseChapter extends Model
{
    use HasFactory;

    protected $fillable = ['order', 'id'];

    public function chapterItems(): HasMany
    {
        return $this->hasMany(CourseChapterItem::class, 'chapter_id', 'id')->orderBy('order');
    }

    /**
     * Get the instructor that owns the CourseChapter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get Course that owns the CourseChapter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($courseChapter) {
            $courseChapter->chapterItems()->each(function ($chapterItem) {
                $chapterItem->delete();
            });
        });
    }
}
