<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Modules\Article\app\Models\Article;
use Modules\Order\Database\factories\EnrollmentFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Enrollment $enrollment) {
            // sleep 1ms
            usleep(1000);
            $enrollment->uuid = Str::ulid();
        });
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id' => 'order_id',
        'user_id' => 'user_id',
        'course_id' => 'course_id',
        'has_access' => 'has_access',
        'tos_status' => 'tos_status',
    ];

    function article(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }


    /**
     * Get the user that owns the Enrollment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
