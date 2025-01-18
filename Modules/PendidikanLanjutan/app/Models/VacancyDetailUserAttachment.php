<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacancyDetailUserAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vacancy_detail_id',
        'vacancy_user_id',
        'file',
    ];

    public function vacancyDetail()
    {
        return $this->belongsTo(VacancyDetail::class);
    }

    public function vacancyUser()
    {
        return $this->belongsTo(VacancyUser::class);
    }
}
