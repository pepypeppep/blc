<?php

namespace Modules\Coaching\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachingSession extends Model
{
    use HasFactory;

    protected $table = 'coaching_sessions';
    protected $guarded = ['id'];

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
}
