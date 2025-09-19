<?php

namespace Modules\Mentoring\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class MentoringSigner extends Model
{
    protected $table = 'mentoring_signers';
    
    protected $fillable = [
        'mentoring_id',
        'user_id',
        'step',
        'type',
    ];

    public function mentoring(): BelongsTo
    {
        return $this->belongsTo(Mentoring::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
