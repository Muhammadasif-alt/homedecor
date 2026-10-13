<?php

namespace Database\Seeders\Concerns;

use App\Models\Media;
use RuntimeException;

trait SeedsMedia
{
    /** Resolve an imported library image by slug (see database/data/image-manifest.php). */
    protected function media(string $slug): int
    {
        return Media::where('path', "media/library/{$slug}")->value('id')
            ?? throw new RuntimeException("Media '{$slug}' not found. Run `php artisan images:import` first.");
    }
}
