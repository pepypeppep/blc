<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use App\Models\User;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'start_at',
        'end_at',
        'year',
    ];

    public function details(){
        return $this->hasMany(VacancyDetail::class);
    }

    public function unors()
    {
        return $this->belongsToMany(Unor::class, 'vacancy_unors', 'vacancy_id', 'unor_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'vacancy_users')
                    ->using(VacancyUser::class)
                    ->withPivot('status', 'sk_file')
                    ->withTimestamps();
    }

}
