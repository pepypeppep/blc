<?php

namespace Modules\Coaching\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Coaching\Database\factories\CoachingSessionDetailFactory;

class CoachingSessionDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['image_url'];

    public function session()
    {
        return $this->belongsTo(CoachingSession::class, 'coaching_session_id');
    }

    public function coachingUser()
    {
        return $this->belongsTo(CoachingUser::class, 'coaching_user_id');
    }

    public function getImageUrlAttribute()
    {
        return route('api.coaching.show.document', ['id' => $this->id, 'module' => 'coaching_session_detail', 'type' => 'image']);
    }
}
