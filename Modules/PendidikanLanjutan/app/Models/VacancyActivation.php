<?php

namespace Modules\PendidikanLanjutan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PendidikanLanjutan\Database\factories\VacancyActivationFactory;

class VacancyActivation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];

    // protected static function newFactory(): VacancyActivationFactory
    // {
    //     //return VacancyActivationFactory::new();
    // }
    protected $guarded = ['id'];

    function vacancyUser(): BelongsTo
    {
        return $this->belongsTo(VacancyUser::class, 'vacancy_user_id', 'id')->withDefault();
    }

    function getFileType()
    {
        $exstract = explode('_', $this->name);
        return $exstract[0];
    }

    function getStatusLabel()
    {
        if ($this->status == 'accepted') {
            return "<span class='badge badge-success bg-success'>Diterima</span>";
        } elseif ($this->status == 'rejected') {
            return "<span class='badge badge-danger bg-danger' data-bs-toggle='tooltip' title='" . $this->note  . "'>Ditolak <i class='fa fa-exclamation-circle' aria-hidden='true'></i></span>";
        } else {
            return "<span class='badge badge-warning bg-warning'>Menunggu Verifikasi</span>";
        }
    }
}
