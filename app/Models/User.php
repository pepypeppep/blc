<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use App\Models\JitsiSetting;
use Laravel\Sanctum\HasApiTokens;
use Modules\Badges\app\Models\Badge;
use Modules\Order\app\Models\Order;
use Illuminate\Notifications\Notifiable;
use Modules\LiveChat\app\Models\Message;
use Modules\Location\app\Models\Country;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Article\app\Models\Article;
use Modules\InstructorRequest\app\Models\InstructorRequest;
use Modules\Order\app\Models\Enrollment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'id',
        'agama',
        'alamat',
        'bup',
        'date_of_birth',
        'email',
        'eselon',
        'forget_password_token',
        'golongan',
        'instansi_id',
        'is_banned',
        'jabatan',
        'jenis_kelamin',
        'name',
        'nip',
        'pangkat',
        'password',
        'pendidikan',
        'phone',
        'place_of_birth',
        'role',
        'status',
        'tanggal_lahir',
        'tempat_lahir',
        'tingkat_pendidikan',
        'tmt_golongan',
        'tmt_jabatan',
        'unor_id',
        'username',
        'verification_token',
        'fcm_token',
        'asn_status',
        'ninebox'
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

    public const ASN_STATUS_PNS = 'PNS';
    public const ASN_STATUS_PPPK = 'PPPK';
    public const ASN_STATUS_LAINNYA = 'Lainnya';

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badge')
            ->withPivot('category')
            ->withTimestamps();
    }

    public function favoriteCourses()
    {
        return $this->belongsToMany(Course::class, 'favorite_course_user')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', UserStatus::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', UserStatus::DEACTIVE);
    }

    public function scopeBanned($query)
    {
        return $query->where('is_banned', UserStatus::BANNED);
    }

    public function scopeUnbanned($query)
    {
        return $query->where('is_banned', UserStatus::UNBANNED);
    }

    public function socialite()
    {
        return $this->hasMany(SocialiteCredential::class, 'user_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    function instructorInfo(): HasOne
    {
        return $this->hasOne(InstructorRequest::class, 'user_id', 'id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function coursesTaken()
    {
        return $this->belongsToMany(Course::class, 'enrollments')->withPivot('has_access')->withTimestamps();
    }

    function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id', 'id');
    }
    function zoom_credential(): HasOne
    {
        return $this->hasOne(ZoomCredential::class, 'instructor_id', 'id');
    }
    function jitsi_credential(): HasOne
    {
        return $this->hasOne(JitsiSetting::class, 'instructor_id', 'id');
    }

    function unor(): HasOne
    {
        return $this->hasOne(Unor::class, 'id', 'unor_id');
    }

    /**
     * Get the instansi that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Delete related instructor request
            $user->instructorInfo()->delete();
        });
    }


    public function isEnrolledInCourse(Course $course)
    {

        $result =  Enrollment::query()
            ->where('user_id', $this->id)
            ->where('course_id', $course->id)
            ->where('has_access', true)
            ->first();
        return $result ? true : false;
    }

    public function canAccessContinuingEducation()
    {
        return $this->asn_status == User::ASN_STATUS_PNS;
    }

    public function isInstructor()
    {
        return $this->role === 'instructor';
    }
}