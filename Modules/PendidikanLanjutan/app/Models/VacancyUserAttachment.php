<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\Database\factories\VacancyUserAttachmentFactory;

class VacancyUserAttachment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the vacancyuser that owns the VacancyUserAttachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancyuser(): BelongsTo
    {
        return $this->belongsTo(VacancyUser::class, 'vacancy_user_id', 'id');
    }

    /**
     * Get the vacancyattachment that owns the VacancyUserAttachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancyattachment(): BelongsTo
    {
        return $this->belongsTo(VacancyAttachment::class, 'vacancy_attachment_id', 'id');
    }

    public function scopeSyarat()
    {
        return $this->where('category', 'syarat');
    }

    public function scopeLampiran()
    {
        return $this->where('category', 'lampiran');
    }
}
