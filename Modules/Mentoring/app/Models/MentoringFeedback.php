<?php

namespace Modules\Mentoring\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentoringFeedback extends Model
{
    use HasFactory;

    protected $table = 'mentoring_feedbacks';
    protected $guarded = ['id'];

    public function Mentoring() {
        return $this->belongsTo(Mentoring::class);
    }
}
