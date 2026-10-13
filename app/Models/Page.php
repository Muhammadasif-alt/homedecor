<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasSeo, SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'template', 'body', 'meta_title', 'meta_description',
        'canonical_url', 'og_media_id', 'robots_index', 'is_published',
    ];

    protected function casts(): array
    {
        return ['robots_index' => 'boolean', 'is_published' => 'boolean'];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order')->orderBy('id');
    }

    public function enabledSections(): HasMany
    {
        return $this->sections()->where('is_enabled', true);
    }

    public static function findBySlug(string $slug): self
    {
        return static::where('slug', $slug)->where('is_published', true)
            ->with(['enabledSections.media', 'ogMedia'])->firstOrFail();
    }

    /** First enabled section of a type — used when a template needs a specific block. */
    public function section(string $type): ?PageSection
    {
        return $this->enabledSections->firstWhere('type', $type);
    }
}
