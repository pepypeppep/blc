<?php

namespace Modules\PendidikanLanjutan\app\Models;

use App\Models\Unor;
use App\Models\User;
use App\Models\Instansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Get the study that owns the Vacancy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    /**
     * Get all of the users for the Vacancy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(VacancyUser::class);
    }

    /**
     * Get the instansi that owns the Vacancy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    public function educationLevel()
    {
        return ucwords(str_replace('_', ' ', $this->education_level));
    }

    public function employmentStatus()
    {
        return $this->employment_status == 'tidak_diberhentikan_dari_jabatan' ? 'tidak diberhentikan dari jabatan' : 'diberhentikan dari jabatan';
    }
}
