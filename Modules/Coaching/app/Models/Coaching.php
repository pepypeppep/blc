<?php

namespace Modules\Coaching\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Coaching\Database\factories\CoachingFactory;

/**
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $goal
 * @property string $reality
 * @property string $option
 * @property string $way_forward
 * @property string $success_indicator
 * @property int $total_session
 * @property int $coach_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property ?User $coach
 * @property CoachingSession[] $coachingSessions
 * @property User[] $coachees
 * @property User[] $joinedCoachees
 * @property User[] $respondedCoachees
 * @property Collection<int, CoachingSigner> $signers
 * @property string $stat
 */
class Coaching extends Model
{
    use HasFactory;

    protected $table = 'coachings';
    protected $guarded = ['id'];
    protected $appends = ['spt_url'];

    public const STATUS_DRAFT = "Draft";
    public const STATUS_CONSENSUS = "Konsensus";
    public const STATUS_PROCESS = "Proses";
    public const STATUS_VERIFICATION = "Verifikasi";
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

    public function respondedCoachees()
    {
        return $this->belongsToMany(User::class, 'coaching_users')
            ->using(CoachingUser::class)
            ->withPivot(['id', 'is_joined', 'joined_at', 'notes', 'final_report'])
            ->withTimestamps()
            ->wherePivotNotNull('is_joined');
    }

    public function completedCoachingUsers()
    {
        return $this->hasMany(CoachingUser::class)
            ->whereNotNull('is_joined')
            ->whereNotNull('final_report');
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
        if ($this->status === $this::STATUS_VERIFICATION) {
            return [
                'label' => 'Verifikasi',
                'color' => 'warning'
            ];
        }
        return [
            'label' => 'Unknown',
            'color' => 'secondary'
        ];
    }

    public function isAllCoacheesAssessed(): bool
    {
        return !CoachingUser::where('coaching_id', $this->id)
            ->where('is_joined', true)
            ->whereDoesntHave('assessment')
            ->exists();
    }

    public function isProcessOrDone(): bool
    {
        // return in_array($this->status, [self::STATUS_PROCESS, self::STATUS_DONE]);
        return in_array($this->status, [self::STATUS_PROCESS, self::STATUS_VERIFICATION, self::STATUS_DONE]);
    }

    public function getSptUrlAttribute()
    {
        return route('api.coaching.show.document', ['id' => $this->id, 'module' => 'coaching', 'type' => 'spt']);
    }

    public function signers()
    {
        return $this->hasMany(CoachingSigner::class);
    }
}
