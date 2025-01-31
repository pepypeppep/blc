<?php

namespace Modules\Article\app\Models;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    use HasFactory;

    protected $fillable = ['article_id', 'tag_id'];

    public function article(): ?BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function tag(): ?BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
