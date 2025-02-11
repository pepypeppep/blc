<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\Database\factories\VacancyAttachmentFactory;

class VacancyAttachment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the vacancy that owns the VacancyAttachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function vacancyUserAttachments()
    {
        return $this->hasMany(VacancyUserAttachment::class);
    }
}
