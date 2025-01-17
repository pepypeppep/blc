<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_id',
        'name',
        'description',
        'start_at',
        'end_at',
        'year',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'vacancy_users')
                    ->using(VacancyUser::class)
                    ->withPivot('status', 'sk_file')
                    ->withTimestamps();
    }

    public function details(){
        return $this->hasMany(VacancyDetail::class);
    }
    
}
