<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function scopeSyarat()
    {
        return $this->where('category', 'syarat');
    }

    public function scopeLampiran()
    {
        return $this->where('category', 'lampiran');
    }

    public function attachment()
    {
        return $this->hasMany(VacancyUserAttachment::class);
    }
}
