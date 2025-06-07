<?php

namespace Modules\Mentoring\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Mentoring extends Model
{
    use HasFactory;

    protected $table = 'mentoring';
    protected $guarded = ['id'];

    public const STATUS_DRAFT = "Draft";
    public const STATUS_SUBMISSION = "Pengajuan";
    public const STATUS_PROCESS = "Proses";
    public const STATUS_DONE = "Selesai";
    public const STATUS_REJECT = "Tolak";

    public function mentor() : BelongsTo {
        return $this->belongsTo(User::class, 'mentor_id', 'id');
    }

    public function mentee() : BelongsTo {
        return $this->belongsTo(User::class, 'mentee_id', 'id');
    }

    public function mentoringSessions() {
        return $this->hasMany(MentoringSession::class);
    }

    public function isProcessOrDone(): bool
    {
        return in_array($this->status, [self::STATUS_PROCESS, self::STATUS_DONE]);
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

}
