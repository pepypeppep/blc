<?php

namespace Modules\Article\app\Models;

use App\Models\Admin;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'verificator_id',
        'thumbnail',
        'category',
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

    public function tags(): ?HasMany
    {
        return $this->hasMany(ArticleTag::class, 'article_id');
    }

    public function articleTags(): ?BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags', 'article_id', 'tag_id')->select('id', 'name');
    }
}
