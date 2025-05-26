<?php

namespace Modules\PendidikanLanjutan\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\Database\factories\VacancyUserDirectFactory;

class VacancyUserDirect extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    protected $guarded = ['id'];

    // protected static function newFactory(): VacancyUserDirectFactory
    // {
    //     //return VacancyUserDirectFactory::new();
    // }

    /**
     * Get the user that owns the VacancyUserDirect
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vacancy that owns the VacancyUserDirect
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
