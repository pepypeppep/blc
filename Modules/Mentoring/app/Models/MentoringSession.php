<?php

namespace Modules\Mentoring\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentoringSession extends Model
{
    use HasFactory;

    protected $table = 'mentoring_sessions';
    protected $guarded = ['id'];
    protected $appends = ['image_url'];

    public const STATUS_PENDING = 'pending';
    public const STATUS_REPORTED = 'reported';
    public const STATUS_REVIEWED = 'reviewed';

    public function Mentoring()
    {
        return $this->belongsTo(Mentoring::class);
    }

    public function getImageUrlAttribute()
    {
        return route('api.mentoring.show.document.session', ['id' => $this->id, 'type' => 'image']);
    }
}
