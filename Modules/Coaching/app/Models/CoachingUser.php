<?php

namespace Modules\Coaching\App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class CoachingUser extends Pivot
{
    protected $table = 'coaching_users';

    protected $fillable = [
        'coaching_id',
        'user_id',
        'status',
        'joined_at',
        'notes'
    ];

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
}