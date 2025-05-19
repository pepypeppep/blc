<?php

namespace Modules\CertificateRecognition\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\CertificateRecognition\app\Models\CertificateRecognitionEnrollment;
use App\Models\Instansi;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;

class CertificateRecognition extends Model
{
    use HasFactory;

    public const IS_APPROVED_PENDING = 'pending';
    public const IS_APPROVED_APPROVED = 'approved';
    public const IS_APPROVED_REJECTED = 'rejected';

    public const STATUS_IS_DRAFT = 'is_draft';
    public const STATUS_VERIFICATION = 'verification';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PUBLISHED = 'published';

    public const CERTIFICATE_STATUS_PENDING = 'pending';
    public const CERTIFICATE_STATUS_PROCESS = 'process';
    public const CERTIFICATE_STATUS_FINISH = 'finish';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    protected $guarded = ['id'];

    // protected static function newFactory(): CertificateRecognitionFactory
    // {
    //     //return CertificateRecognitionFactory::new();
    // }

    public function getStatAttribute()
    {
        if ($this->status === $this::STATUS_IS_DRAFT) {
            return [
                'label' => 'Draft',
                'color' => 'warning'
            ];
        }
        if ($this->status === $this::STATUS_VERIFICATION) {
            return [
                'label' => 'Verification',
                'color' => 'info'
            ];
        }
        if ($this->status === $this::STATUS_REJECTED) {
            return [
                'label' => 'Rejected',
                'color' => 'danger'
            ];
        }
        if ($this->status === $this::STATUS_PUBLISHED) {
            return [
                'label' => 'Published',
                'color' => 'success'
            ];
        }
        return [
            'label' => 'Unknown',
            'color' => 'secondary'
        ];
    }

    public function getApprovalStatAttribute()
    {
        if ($this->is_approved === $this::IS_APPROVED_PENDING) {
            return [
                'label' => 'Pending',
                'color' => 'warning'
            ];
        }
        if ($this->is_approved === $this::IS_APPROVED_APPROVED) {
            return [
                'label' => 'Approved',
                'color' => 'success'
            ];
        }
        if ($this->is_approved === $this::IS_APPROVED_REJECTED) {
            return [
                'label' => 'Rejected',
                'color' => 'danger'
            ];
        }
        return [
            'label' => 'Unknown',
            'color' => 'secondary'
        ];
    }

    public function getCertificateStatAttribute()
    {
        if ($this->certificate_status === $this::CERTIFICATE_STATUS_PENDING) {
            return [
                'label' => 'Pending',
                'color' => 'warning'
            ];
        }
        if ($this->certificate_status === $this::CERTIFICATE_STATUS_PROCESS) {
            return [
                'label' => 'Process',
                'color' => 'info'
            ];
        }
        if ($this->certificate_status === $this::CERTIFICATE_STATUS_FINISH) {
            return [
                'label' => 'Finish',
                'color' => 'success'
            ];
        }
        return [
            'label' => 'Unknown',
            'color' => 'secondary'
        ];
    }

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

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function certificate()
    {
        return $this->belongsTo(CertificateBuilder::class);
    }
}
