<?php

namespace Modules\Pengumuman\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'pengumumans';

    public const STATUS_SHOW = "show";
    public const STATUS_HIDE = "hide";

    public function getStatAttribute()
    {
        if ($this->status === $this::STATUS_SHOW) {
            return [
                'label' => 'SHOW',
                'color' => 'primary'
            ];
        }
        if ($this->status === $this::STATUS_HIDE) {
            return [
                'label' => 'HIDE',
                'color' => 'secondary'
            ];
        }
        return [
            'label' => 'Unknown',
            'color' => 'secondary'
        ];
    }
}
