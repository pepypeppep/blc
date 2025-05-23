<?php

namespace Modules\PendidikanLanjutan\app\Models;

use App\Models\Unor;
use App\Models\User;
use App\Models\Instansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Get the detail associated with the Vacancy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(VacancyDetail::class);
    }

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

    public function isEligible(User $user): ?string
    {
        if ($this->instansi_id && $this->instansi_id !== $user->instansi_id) {
            return 'Anda tidak memiliki akses ke lowongan ini';
        }

        if ($this->open_at > now()) {
            return 'Lowongan belum dibuka';
        }

        if ($this->close_at < now()) {
            return 'Lowongan sudah ditutup';
        }

        return null;
    }
}
