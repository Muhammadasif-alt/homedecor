<?php

namespace App\Models\Concerns;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Shared SEO helpers for models with meta_title / meta_description / og_media_id columns.
 */
trait HasSeo
{
    public function ogMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_media_id');
    }

    public function seoTitle(): string
    {
        return $this->meta_title ?: ($this->title ?? $this->name);
    }
}
