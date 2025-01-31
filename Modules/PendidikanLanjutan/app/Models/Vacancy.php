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

    protected $guarded = ['id'];
}
