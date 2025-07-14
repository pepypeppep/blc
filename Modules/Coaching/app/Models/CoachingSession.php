<?php

namespace Modules\Coaching\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Coaching\Database\factories\CoachingSessionFactory;

class CoachingSession extends Model
{
    use HasFactory;

    protected $table = 'coaching_sessions';
    protected $guarded = ['id'];

    public function coaching()
    {
        return $this->belongsTo(Coaching::class);
    }

    public function details()
    {
        return $this->hasMany(CoachingSessionDetail::class);
    }
}
