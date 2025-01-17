<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unor extends Model
{
    use HasFactory;

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function vacancies()
    {
        return $this->belongsToMany(Vacancy::class, 'vacancy_unors', 'unor_id', 'vacancy_id');
    }
}
