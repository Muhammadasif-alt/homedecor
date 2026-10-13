<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasSeo, SoftDeletes, Sortable;

    protected $fillable = [
        'name', 'slug', 'icon', 'short_description', 'hero_title', 'hero_description', 'hero_media_id',
        'intro', 'included', 'styles', 'benefits', 'materials', 'process',
        'meta_title', 'meta_description', 'canonical_url', 'og_media_id',
        'is_featured', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'included' => 'array', 'styles' => 'array', 'benefits' => 'array',
            'materials' => 'array', 'process' => 'array',
            'is_featured' => 'boolean', 'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'hero_media_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ServiceSection::class)->orderBy('sort_order');
    }

    public function gallery(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'service_media')->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function related(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'service_related', 'service_id', 'related_service_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->where('is_published', true)->orderBy('sort_order');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class)->where('is_approved', true)->orderBy('sort_order');
    }

    public function url(): string
    {
        return route('services.show', $this);
    }
}
