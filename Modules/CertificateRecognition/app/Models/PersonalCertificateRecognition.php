<?php

namespace Modules\CertificateRecognition\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CertificateRecognition\Database\factories\PersonalCertificateRecognitionFactory;

class PersonalCertificateRecognition extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the competency_development that owns the PersonalCertificateRecognition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competency_development(): BelongsTo
    {
        return $this->belongsTo(CompetencyDevelopment::class);
    }

    public function getStatAttribute(): array
    {
        if ($this->status === 'pending') {
            return [
                'label' => 'Tunda',
                'color' => 'secondary'
            ];
        }
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
        if ($this->status === 'reject') {
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
}
