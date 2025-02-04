<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;

class VacancyReport extends Model
{
    use HasFactory;
    protected $fillable = ['vacancy_user_id', 'name', 'file', 'status', 'note'];

    function vacancyUser(): BelongsTo
    {
        return $this->belongsTo(VacancyUser::class, 'vacancy_user_id', 'id')->withDefault();
    }
}
