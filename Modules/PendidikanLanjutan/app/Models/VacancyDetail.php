<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\Database\factories\VacancyDetailFactory;

class VacancyDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public const EMPLOYMENT_STATUS = [
        'tidak' => 'Tidak diberhentikan dari Jabatan',
        'diberhentikan' => 'Diberhentikan dari Jabatan',
    ];

    public const COST_TYPE = [
        'apbd' => 'APBD',
        'non_apbd' => 'Non APBD',
        'mandiri' => 'Mandiri',
    ];

    /**
     * Get the vacancy that owns the VacancyDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function employmentStatus()
    {
        return $this->employment_status == 'tidak_diberhentikan_dari_jabatan' ? 'tidak diberhentikan dari jabatan' : 'diberhentikan dari jabatan';
    }

    public function costType()
    {
        return $this->cost_type == 'apbd' ? 'APBD' : ($this->cost_type == 'non_apbd' ? 'Non APBD' : 'Mandiri');
    }
}
