<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\Database\factories\VacancyActivationFactory;

class VacancyActivation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): VacancyActivationFactory
    // {
    //     //return VacancyActivationFactory::new();
    // }
    protected $guarded = ['id'];

    function vacancyUser(): BelongsTo
    {
        return $this->belongsTo(VacancyUser::class, 'vacancy_user_id', 'id')->withDefault();
    }
}
