<?php

namespace Modules\Article\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleComment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'description',
        'author_id',
        'article_id',
    ];

    public function post(): ?BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
