<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use App\Models\User;

class Vacancy extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
