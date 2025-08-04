<?php

namespace Modules\Coaching\App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Modules\Coaching\app\Models\Coaching;
use Modules\Coaching\app\Models\CoachingAssessment;

class CoachingUser extends Pivot
{
    protected $table = 'coaching_users';
    protected $appends = ['final_report_url'];

    protected $guarded = ['id'];

    public function coaching(): BelongsTo
    {
        return $this->belongsTo(Coaching::class);
    }

    public function coachee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assessment()
    {
        return $this->hasOne(CoachingAssessment::class, 'coaching_user_id');
    }

    public function scopeJoined($query)
    {
        return $query->where('is_joined', true);
    }

    public function scopeForCoach($query, $coachId, $coachingId)
    {
        return $query->whereHas('coaching', function ($q) use ($coachId, $coachingId) {
            $q->where('id', $coachingId)->where('coach_id', $coachId);
        });
    }

    public function isRejected()
    {
        return $this->is_joined == 0 && $this->notes != null;
    }

    public function getFinalReportUrlAttribute()
    {
        return route('api.coaching.show.document', ['id' => $this->id, 'module' => 'coaching_user', 'type' => 'final_report']);
    }
}
