<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected $appends = ['file_path_url'];

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

    protected function filePathUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->participant_file ? route('api.courses.get-file', ['type' => 'rtl', 'id' => $this->id])
                : null
        );
    }
}
