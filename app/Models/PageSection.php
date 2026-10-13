<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A reusable content block on a page. `type` maps to a Blade component in
 * resources/views/components/sections/{type}.blade.php.
 */
class PageSection extends Model
{
    public const TYPES = [
        'announcement' => 'Announcement bar',
        'hero' => 'Hero banner',
        'stats' => 'Trust indicators (verified numbers only)',
        'text_image' => 'Text and image',
        'services' => 'Service cards',
        'features' => 'Features / Why choose us',
        'projects' => 'Featured projects',
        'before_after' => 'Before & After (real pairs only)',
        'process' => 'Process steps',
        'styles' => 'Decor styles',
        'gallery' => 'Image gallery',
        'testimonials' => 'Testimonials',
        'blog' => 'Latest articles',
        'faqs' => 'FAQs',
        'team' => 'Team members',
        'portfolio' => 'Filterable portfolio grid',
        'contact' => 'Contact details + form',
        'consultation_form' => 'Consultation request form',
        'service_areas' => 'Service areas list',
        'cta' => 'Call to action',
    ];

    protected $fillable = ['page_id', 'type', 'eyebrow', 'heading', 'body', 'media_id', 'data', 'is_enabled', 'sort_order'];

    protected function casts(): array
    {
        return ['data' => 'array', 'is_enabled' => 'boolean'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->data, $key, $default);
    }
}
