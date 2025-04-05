<?php

namespace Modules\Pengetahuan\app\Models;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Order\app\Models\Enrollment;

class Pengetahuan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_VERIFICATION = 'verification';

    public function getStatAttribute()
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
        return [
            'label' => 'Unknown',
            'color' => 'secondary'
        ];
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function pengetahuanTags(): ?BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'pengetahuan_tags', 'pengetahuan_id', 'tag_id')->select('id', 'name');
    }
}
