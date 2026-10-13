<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasSeo, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'featured_media_id', 'author_id', 'service_id',
        'status', 'published_at', 'meta_title', 'meta_description', 'canonical_url',
        'og_media_id', 'robots_index',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'robots_index' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Published and not scheduled for the future. */
    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_media_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category');
    }

    public function isLive(): bool
    {
        return $this->status === 'published' && $this->published_at?->isPast();
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 220));
    }

    public function url(): string
    {
        return route('blog.show', $this);
    }
}
