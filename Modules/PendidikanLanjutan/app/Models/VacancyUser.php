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

    // draft_verification,draft_assessment,rejected,assessment,eligible,ineligible,report,extend,done
    public const STATUS_REGISTER = 'register';
    public const STATUS_VERIFICATION = 'verification';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_ASSESSMENT = 'assessment';
    public const STATUS_ELIGIBLE = 'eligible';
    public const STATUS_INELIGIBLE = 'ineligible';
    public const STATUS_REPORT = 'report';
    public const STATUS_EXTEND = 'extend';
    public const STATUS_DONE = 'done';


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

    public function getStatAttribute()
    {
        if ($this->status == 'register') {
            return [
                'label' => 'Regiser',
                'color' => 'warning'
            ];
        }

        if ($this->status == 'verification') {
            return [
                'label' => 'Verifikasi',
                'color' => 'warning'
            ];
        }

        if ($this->status == 'rejected') {
            return [
                'label' => 'Ditolak',
                'color' => 'danger'
            ];
        }

        if ($this->status == 'assessment') {
            return [
                'label' => 'Asesmen',
                'color' => 'primary'
            ];
        }

        if ($this->status == 'eligible') {
            return [
                'label' => 'Eligible',
                'color' => 'success'
            ];
        }

        if ($this->status == 'ineligible') {
            return [
                'label' => 'Ineligible',
                'color' => 'danger'
            ];
        }

        if ($this->status == 'report') {
            return [
                'label' => 'Laporan',
                'color' => 'primary'
            ];
        }

        if ($this->status == 'extend') {
            return [
                'label' => 'Perpanjangan',
                'color' => 'primary'
            ];
        }

        if ($this->status == 'done') {
            return [
                'label' => 'Selesai',
                'color' => 'success'
            ];
        }

        return [
            'label' => $this->status,
            'color' => 'warning'
        ];
    }
}
