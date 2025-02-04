<?php

namespace Modules\PendidikanLanjutan\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VacancyUser extends Pivot
{
    use HasFactory;

    protected $table = 'vacancy_users';

    protected $guarded = ['id'];

    /**
     * Get the user that owns the VacancyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vacancy that owns the VacancyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
