<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\Database\factories\VacancyLogsFactory;

class VacancyLogs extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
