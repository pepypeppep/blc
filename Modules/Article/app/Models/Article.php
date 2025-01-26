<?php

namespace Modules\Article\app\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'verificator_id',
        'thumbnail',
        'title',
        'slug',
        'content',
        'description',
        'status',
        'instansi',
        'views',
        'visibility',
        'allow_comments',
        'published_at',
    ];


    public function comments(): ?HasMany
    {
        return $this->hasMany(ArticleComment::class, 'article_id');
    }

    public function author(): ?BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
