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
        return $this->belongsTo(VacancyUser::class);
    }

    /**
     * Get the vacancyattachment that owns the VacancyUserAttachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancyAttachment(): BelongsTo
    {
        return $this->belongsTo(VacancyAttachment::class);
    }
}
