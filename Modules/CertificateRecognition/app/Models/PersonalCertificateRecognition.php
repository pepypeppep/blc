<?php

namespace Modules\CertificateRecognition\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Article\app\Models\Article;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CertificateRecognition\Database\factories\PersonalCertificateRecognitionFactory;

class PersonalCertificateRecognition extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['stat', 'report_file_url', 'certificate_file_url', 'award_file_url'];

    /**
     * Get the user that owns the PersonalCertificateRecognition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the competency_development that owns the PersonalCertificateRecognition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competency_development(): BelongsTo
    {
        return $this->belongsTo(CompetencyDevelopment::class);
    }

    /**
     * Get the article associated with the PersonalCertificateRecognition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function article(): HasOne
    {
        return $this->hasOne(Article::class, 'personal_certificate_recognition_id');
    }

    public function getStatAttribute(): array
    {
        // if ($this->status === 'pending') {
        //     return [
        //         'label' => 'Tunda',
        //         'color' => 'secondary'
        //     ];
        // }
        if ($this->status === 'draft') {
            return [
                'label' => 'Draft',
                'color' => 'warning'
            ];
        }
        if ($this->status === 'process') {
            return [
                'label' => 'Proses',
                'color' => 'info'
            ];
        }
        if ($this->status === 'verification') {
            return [
                'label' => 'Verifikasi',
                'color' => 'warning'
            ];
        }
        if ($this->status === 'done') {
            return [
                'label' => 'Selesai',
                'color' => 'success'
            ];
        }
        if ($this->status === 'rejected') {
            return [
                'label' => 'Ditolak',
                'color' => 'danger'
            ];
        }
        return [
            'label' => 'Unknown',
            'color' => 'secondary'
        ];
    }

    protected function getReportFileUrlAttribute()
    {
        return route('student.pengakuan-sertifikat.attachment', [$this->id, 'report_file']);
    }

    protected function getCertificateFileUrlAttribute()
    {
        return route('student.pengakuan-sertifikat.attachment', [$this->id, 'certificate_file']);
    }

    protected function getAwardFileUrlAttribute()
    {
        return route('student.pengakuan-sertifikat.attachment', [$this->id, 'award_file']);
    }
}
