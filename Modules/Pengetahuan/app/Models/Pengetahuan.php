<?php

namespace Modules\Pengetahuan\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengetahuan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_VERIFICATION = 'verification';

    public function getStatusAttribute()
    {
        if ($this->status == $this->STATUS_DRAFT) {
            return [
                'label' => 'Draft',
                'color' => 'warning'
            ];
        }
        if ($this->status == $this->STATUS_PUBLISHED) {
            return [
                'label' => 'Published',
                'color' => 'success'
            ];
        }
        if ($this->status == $this->STATUS_REJECTED) {
            return [
                'label' => 'Rejected',
                'color' => 'danger'
            ];
        }
        if ($this->status == $this->STATUS_VERIFICATION) {
            return [
                'label' => 'Verification',
                'color' => 'info'
            ];
        }
    }
}
