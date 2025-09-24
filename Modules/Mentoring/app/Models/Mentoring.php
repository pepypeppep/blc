<?php

namespace Modules\Mentoring\app\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mentoring extends Model
{
    use HasFactory;

    protected $table = 'mentoring';
    protected $guarded = ['id'];
    protected $appends = ['mentor_availability_letter_url', 'final_report_url', 'certificate_url'];

    public const STATUS_DRAFT = "Draft";
    public const STATUS_SUBMISSION = "Pengajuan";
    public const STATUS_PROCESS = "Proses";
    public const STATUS_EVALUATION = "Penilaian";
    public const STATUS_VERIFICATION = "Verifikasi";
    public const STATUS_DONE = "Selesai";
    public const STATUS_REJECT = "Tolak";


    protected static function booted(): void
    {
        static::creating(function (Mentoring $mentoring) {
            usleep(1000);
            $mentoring->uuid = Str::ulid();
        });
    }


    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id', 'id');
    }

    public function mentee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentee_id', 'id');
    }

    public function mentoringSessions()
    {
        return $this->hasMany(MentoringSession::class);
    }

    public function getStatAttribute(): array
    {
        if ($this->status === $this::STATUS_DRAFT) {
            return [
                'label' => 'Draft',
                'color' => 'secondary'
            ];
        }
        if ($this->status === $this::STATUS_SUBMISSION) {
            return [
                'label' => 'Pengajuan',
                'color' => 'warning'
            ];
        }
        if ($this->status === $this::STATUS_PROCESS) {
            return [
                'label' => 'Proses',
                'color' => 'info'
            ];
        }
        if ($this->status === $this::STATUS_EVALUATION) {
            return [
                'label' => 'Penilaian',
                'color' => 'primary'
            ];
        }
        if ($this->status === $this::STATUS_VERIFICATION) {
            return [
                'label' => 'Verifikasi',
                'color' => 'warning'
            ];
        }
        if ($this->status === $this::STATUS_DONE) {
            return [
                'label' => 'Selesai',
                'color' => 'success'
            ];
        }
        if ($this->status === $this::STATUS_REJECT) {
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

    public function isProcessOrDone(): bool
    {
        return in_array($this->status, [self::STATUS_PROCESS, self::STATUS_DONE]);
    }

    public function isProcessOrEvaluationOrDone(): bool
    {
        return in_array($this->status, [self::STATUS_PROCESS, self::STATUS_EVALUATION, self::STATUS_VERIFICATION, self::STATUS_DONE]);
    }

    public function signers()
    {
        return $this->hasMany(MentoringSigner::class);
    }

    public function getDocumentResponse($column)
    {
        if (!$this->$column) {
            abort(404);
        }
        if (Storage::disk('private')->exists($this->$column)) {
            return Storage::disk('private')->response($this->$column);
        }
        abort(404);
    }

    public function getFinalReportUrlAttribute()
    {
        return route('api.mentoring.show.document', ['id' => $this->id, 'type' => 'final_report']);
    }

    public function getMentorAvailabilityLetterUrlAttribute()
    {
        return route('api.mentoring.show.document', ['id' => $this->id, 'type' => 'mentor_availability_letter']);
    }

    public function getCertificateUrlAttribute()
    {
        return route('api.mentoring.show.document', ['id' => $this->id, 'type' => 'certificate_path']);
    }

    public function feedback()
    {
        return $this->hasOne(MentoringFeedback::class);
    }

    /**
     * Get the review associated with the Mentoring
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function review(): HasOne
    {
        return $this->hasOne(MentoringReview::class);
    }
}
