<?php

namespace Modules\Coaching\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachingSession extends Model
{
    use HasFactory;

    protected $table = 'coaching_sessions';
    protected $guarded = ['id'];
    protected $appends = ['session_status_count'];

    public const STATUS_PENDING = "Pending";
    public const STATUS_PROCESS = "Process";
    public const STATUS_DONE = "Done";

    public function coaching()
    {
        return $this->belongsTo(Coaching::class);
    }

    public function details()
    {
        return $this->hasMany(CoachingSessionDetail::class);
    }

    public function getSessionStatusCountAttribute()
    {
        $total = $this->coaching->joinedCoachees->count();
        $progress = $this->details->count();
        $reviewed = $this->details->whereNotNull('coaching_note')->whereNotNull('coaching_instructions')->count();

        return [
            'total' => $total,
            'progress' => $progress,
            'reviewed' => $reviewed
        ];
    }
}
