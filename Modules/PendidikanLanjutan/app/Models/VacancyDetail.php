<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacancyDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'vacancy_id',
        'name',
        'category',
        'type',
        'value_type',
        'description',
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function attachments()
    {
        return $this->hasMany(VacancyDetailUserAttachment::class);
    }
}

