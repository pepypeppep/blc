<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUpActionResponse extends Model
{
    protected $fillable = [
        'participant_response',
        'participant_file',
        'instructor_response',
        'score',
        'follow_up_action_id',
        'participant_id',
        'instructor_id',
    ];

    //relation to participant
    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    //relation to instructor
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    //relation to   low_up_action
    public function follow_up_action()
    {
        return $this->belongsTo(FollowUpAction::class);
    }
}
