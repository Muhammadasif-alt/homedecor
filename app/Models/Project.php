<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasSeo, SoftDeletes, Sortable;

    protected $fillable = [
        'title', 'slug', 'project_category_id', 'location', 'summary', 'overview', 'requirements',
        'solution', 'materials', 'cover_media_id', 'before_media_id', 'after_media_id',
        'meta_title', 'meta_description', 'is_featured', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'is_published' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }

    public function beforeMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'before_media_id');
    }

    public function afterMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'after_media_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class)->where('is_approved', true);
    }

    public function hasBeforeAfter(): bool
    {
        return $this->before_media_id && $this->after_media_id;
    }

    public function url(): string
    {
        return route('projects.show', $this);
    }
}
