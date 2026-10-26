<?php

use App\Models\BlogPost;
use App\Models\Media;
use App\Services\ImageOptimizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * The owner asked for the chiminea build guide and its photo to come off the site:
 * a DIY masonry project is off-topic for a home decor studio.
 * The post is soft-deleted (so GuidePostSeeder never recreates it), dropped from its
 * service's further reading, and the photo is removed from the media library.
 */
return new class extends Migration
{
    public function up(): void
    {
        $post = BlogPost::withTrashed()->where('slug', 'how-to-build-a-chiminea')->first();
        $media = Media::where('path', 'media/library/cast-chiminea-log-stacks')->first();

        if ($post) {
            DB::table('blog_post_service')->where('blog_post_id', $post->id)->delete();
            $post->update(['featured_media_id' => null]);
            $post->delete();
        }

        if ($media) {
            $media->delete();
            app(ImageOptimizer::class)->delete($media);
        }

        foreach (['public-nav', 'home-page', 'sitemap-xml'] as $key) {
            Cache::forget($key);
        }
    }

    public function down(): void
    {
        BlogPost::onlyTrashed()->where('slug', 'how-to-build-a-chiminea')->restore();
    }
};
