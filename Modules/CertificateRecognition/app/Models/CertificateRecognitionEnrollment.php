<?php

namespace Modules\CertificateRecognition\app\Models;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CertificateRecognition\Database\factories\CertificateRecognitionEnrollmentFactory;

class CertificateRecognitionEnrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = ['id'];

    // protected static function newFactory(): CertificateRecognitionFactory
    // {
    //     //return CertificateRecognitionFactory::new();
    // }

    /**
     * Get the user that owns the CertificateRecognitionEnrollment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the certificateRecognition that owns the CertificateRecognitionEnrollment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function certificateRecognition(): BelongsTo
    {
        return $this->belongsTo(CertificateRecognition::class);
    }
}
