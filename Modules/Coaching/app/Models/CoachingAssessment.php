<?php

namespace Modules\Coaching\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Coaching\Database\factories\CoachingAssessmentFactory;

class CoachingAssessment extends Model
{
    use HasFactory;

    protected $table = 'coaching_assesments';
    protected $guarded = ['id'];
    
    public function coachingUser()
    {
        return $this->belongsTo(CoachingUser::class);
    }
}
