<?php

namespace Modules\CertificateRecognition\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\CertificateRecognition\app\Models\CertificateRecognitionEnrollment;

class CertificateRecognition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    protected $guarded = ['id'];

    // protected static function newFactory(): CertificateRecognitionFactory
    // {
    //     //return CertificateRecognitionFactory::new();
    // }

    /**
     * Get all of the enrollments for the CertificateRecognition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CertificateRecognitionEnrollment::class);
    }

    /**
     * The users that belong to the CertificateRecognition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, CertificateRecognitionEnrollment::class, 'certificate_recognition_id', 'user_id');
    }
}
