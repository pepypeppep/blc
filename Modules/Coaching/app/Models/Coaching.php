<?php

namespace Modules\Coaching\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Coaching\Database\factories\CoachingFactory;

class Coaching extends Model
{
    use HasFactory;

    protected $table = 'coachings';
    protected $guarded = ['id'];

    public const STATUS_DRAFT = "Draft";
    public const STATUS_CONSENSUS = "Konsensus";
    public const STATUS_PROCESS = "Proses";
    public const STATUS_EVALUATION = "Penilaian";
    public const STATUS_DONE = "Selesai";

    public function coachees()
    {
        return $this->belongsToMany(User::class, 'coaching_users')
            ->using(CoachingUser::class)
            ->withPivot(['id', 'is_joined', 'joined_at', 'notes', 'final_report'])
            ->withTimestamps();
    }

    public function joinedCoachees()
    {
        return $this->belongsToMany(User::class, 'coaching_users')
            ->using(CoachingUser::class)
            ->withPivot(['id', 'is_joined', 'joined_at', 'notes', 'final_report'])
            ->withTimestamps()
            ->wherePivot('is_joined', true);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function coachingSessions()
    {
        return $this->hasMany(CoachingSession::class);
    }

    public function getStatAttribute(): array
    {
        if ($this->status === $this::STATUS_DRAFT) {
            return [
                'label' => 'Draft',
                'color' => 'secondary'
            ];
        }
        if ($this->status === $this::STATUS_CONSENSUS) {
            return [
                'label' => 'Konsensus',
                'color' => 'warning'
            ];
        }
        if ($this->status === $this::STATUS_PROCESS) {
            return [
                'label' => 'Proses',
                'color' => 'info'
            ];
        }
        if ($this->status === $this::STATUS_DONE) {
            return [
                'label' => 'Selesai',
                'color' => 'success'
            ];
        }
        return [
            'label' => 'Unknown',
            'color' => 'secondary'
        ];
    }

    public function isProcessOrEvaluationOrDone(): bool
    {
        return in_array($this->status, [self::STATUS_PROCESS, self::STATUS_EVALUATION, self::STATUS_DONE]);
    }
}
