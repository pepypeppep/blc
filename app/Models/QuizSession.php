<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'questions',
        'answers',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'questions' => 'array',
        'answers' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}