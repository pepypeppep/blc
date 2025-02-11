<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use App\Models\JitsiSetting;
use Laravel\Sanctum\HasApiTokens;
use Modules\Order\app\Models\Order;
use Illuminate\Notifications\Notifiable;
use Modules\LiveChat\app\Models\Message;
use Modules\Location\app\Models\Country;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\InstructorRequest\app\Models\InstructorRequest;
use Modules\PendidikanLanjutan\app\Models\Unor;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'role',
        'name',
        'email',
        'password',
        'status',
        'is_banned',
        'verification_token',
        'forget_password_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];
    public function favoriteCourses() {
        return $this->belongsToMany(Course::class, 'favorite_course_user')->withTimestamps();
    }

    public function scopeActive($query) {
        return $query->where('status', UserStatus::ACTIVE);
    }

    public function scopeInactive($query) {
        return $query->where('status', UserStatus::DEACTIVE);
    }

    public function scopeBanned($query) {
        return $query->where('is_banned', UserStatus::BANNED);
    }

    public function scopeUnbanned($query) {
        return $query->where('is_banned', UserStatus::UNBANNED);
    }

    public function socialite() {
        return $this->hasMany(SocialiteCredential::class, 'user_id');
    }

    function instructorInfo(): HasOne {
        return $this->hasOne(InstructorRequest::class, 'user_id', 'id');
    }

    public function courses() {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function coursesTaken()
    {
        return $this->belongsToMany(Course::class, 'course_participants');
    }

    function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    function orders(): HasMany {
        return $this->hasMany(Order::class, 'buyer_id', 'id');
    }
    function zoom_credential(): HasOne {
        return $this->hasOne(ZoomCredential::class, 'instructor_id', 'id');
    }
    function jitsi_credential(): HasOne {
        return $this->hasOne(JitsiSetting::class, 'instructor_id', 'id');
    }

    function unor(): HasOne {
        return $this->hasOne(Unor::class, 'id', 'unor_id');
    }

    /**
     * Boot the model.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function ($user) {
            // Delete related instructor request
            $user->instructorInfo()->delete();
        });
    }
}
