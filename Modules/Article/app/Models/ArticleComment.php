<?php

namespace Modules\Article\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Article\Database\factories\ArticleCommentFactory;

class ArticleComment extends Model
{
    use HasFactory;

    public const STATUS_PUBLISHED = "published";
    public const STATUS_UNPUBLISHED = "unpublished";

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = ['id'];

    // protected static function newFactory(): ArticleCommentFactory
    // {
    //     //return ArticleCommentFactory::new();
    // }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all of the reports for the ArticleComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(ArticleCommentReport::class, 'comment_id');
    }
}
