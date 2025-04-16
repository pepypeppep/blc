<?php

namespace Modules\Article\app\Models;

use App\Models\Course;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Order\app\Models\Enrollment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = array('pivot');

    protected $appends = ['thumbnail_url', 'document_url', 'embed_link'];

    
    public const STATUS_DRAFT = "draft";
    public const STATUS_PUBLISHED = "published";
    public const STATUS_REJECTED = "rejected";
    public const STATUS_VERIFICATION = "verification";

    public function getStatAttribute()
    {
        if ($this->status === $this::STATUS_DRAFT) {
            return [
                'label' => 'Draft',
                'color' => 'warning'
            ];
        }
        if ($this->status === $this::STATUS_PUBLISHED) {
            return [
                'label' => 'Published',
                'color' => 'success'
            ];
        }
        if ($this->status === $this::STATUS_REJECTED) {
            return [
                'label' => 'Rejected',
                'color' => 'danger'
            ];
        }
        if ($this->status === $this::STATUS_VERIFICATION) {
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

    public function author(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): ?HasMany
    {
        return $this->hasMany(ArticleTag::class, 'article_id');
    }

    /**
     * Get all of the reviews for the Article
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ArticleReview::class, 'article_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function articleTags(): ?BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags', 'article_id', 'tag_id')->select('id', 'name');
    }


    public function scopeIsPublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function reviewsRating(): float
    {
        $rating = $this->reviews->avg('stars');
        return number_format((float)$rating, 1, '.', '');
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->thumbnail ? route('student.pengetahuan.view.file', ['id' => $this->id]) : null,
        );
    }

    protected function documentUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->file ? route('student.pengetahuan.view.pdf', ['id' => $this->id]) : null,
        );
    }

    protected function embedLink(): Attribute
    {
        return Attribute::make(
            get: function () {
                $link = $this->link;

                if (strpos($link, 'youtube.com/watch') !== false) {
                    parse_str(parse_url($link, PHP_URL_QUERY), $query);
                    $videoId = $query['v'] ?? null;
                } elseif (strpos($link, 'youtu.be/') !== false) {
                    $path = parse_url($link, PHP_URL_PATH);
                    $videoId = trim($path, '/');
                } else {
                    $videoId = null;
                }

                return $videoId ? "https://www.youtube.com/embed/{$videoId}" : $link;
            }
        );
    }
}
