<?php

namespace Modules\Coaching\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachingSigner extends Model
{
    use HasFactory;

    const TYPE_SIGN = 'sign';
    const TYPE_VERIFY = 'verify';

    const FRONT = 2;
    const BACK = 1;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'coaching_id', 'step', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coaching()
    {
        return $this->belongsTo(Coaching::class);
    }
}
