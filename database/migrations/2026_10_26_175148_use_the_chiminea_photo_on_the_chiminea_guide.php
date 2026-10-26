<?php

use App\Models\BlogPost;
use App\Models\Media;
use App\Services\ImageOptimizer;
use Illuminate\Database\Migrations\Migration;

/**
 * The chiminea guide went live with a stand-in indoor fireplace photo. This imports the real
 * chiminea photo into the media library and makes it the guide's featured image.
 * Only runs where the guide already exists; fresh installs get the photo through
 * images:import and GuidePostSeeder.
 */
return new class extends Migration
{
    private const SLUG = 'cast-chiminea-log-stacks';

    private const SOURCE = 'Image/unsplash-chiminea-kayla-am.jpg';

    public function up(): void
    {
        $post = BlogPost::where('slug', 'how-to-build-a-chiminea')->first();
        if (! $post) {
            return;
        }

        $media = Media::where('path', 'media/library/'.self::SLUG)->first()
            ?? app(ImageOptimizer::class)->store(base_path(self::SOURCE), 'media/library', [
                'slug' => self::SLUG,
                'alt' => 'White cast chiminea with a tall chimney on paving, between neat stacks of split firewood',
                'category' => 'outdoor',
                'original_name' => basename(self::SOURCE),
            ]);

        $post->update(['featured_media_id' => $media->id]);
    }

    public function down(): void
    {
        $post = BlogPost::where('slug', 'how-to-build-a-chiminea')->first();
        $fallback = Media::where('path', 'media/library/double-height-living-room-fireplace')->value('id');

        if ($post && $fallback) {
            $post->update(['featured_media_id' => $fallback]);
        }
    }
};
