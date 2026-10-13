<?php

namespace App\Http\Controllers\Admin\Support;

use App\Models\Media;
use Illuminate\Support\Facades\DB;

/**
 * Finds where a media item is referenced, so editors are warned before deleting it.
 * (Foreign keys are nullOnDelete / cascade, so deleting is always safe for the database.)
 */
class MediaUsage
{
    /** table => [column => label] */
    private const REFERENCES = [
        'pages' => ['og_media_id' => 'Page share image'],
        'page_sections' => ['media_id' => 'Page section image'],
        'services' => ['hero_media_id' => 'Service hero', 'og_media_id' => 'Service share image'],
        'service_sections' => ['media_id' => 'Service content block'],
        'service_media' => ['media_id' => 'Service gallery'],
        'projects' => ['cover_media_id' => 'Project cover', 'before_media_id' => 'Project “before”', 'after_media_id' => 'Project “after”'],
        'project_images' => ['media_id' => 'Project gallery'],
        'blog_posts' => ['featured_media_id' => 'Article featured image', 'og_media_id' => 'Article share image'],
        'testimonials' => ['photo_media_id' => 'Testimonial photo'],
    ];

    /** @return array<string, int> label => count */
    public function for(Media $media): array
    {
        $usage = [];
        foreach (self::REFERENCES as $table => $columns) {
            foreach ($columns as $column => $label) {
                $count = DB::table($table)->where($column, $media->id)->count();
                if ($count) {
                    $usage[$label] = $count;
                }
            }
        }

        return $usage;
    }
}
