<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VacancyUser extends Pivot
{
    use HasFactory;

    protected $table = 'vacancy_users';

    protected $fillable = [
        'vacancy_id',
        'user_id',
        'status',
        'sk_file',
    ];

    public function attachments(){
        return $this->hasMany(VacancyDetailUserAttachment::class, 'vacancy_user_id');
    }
}