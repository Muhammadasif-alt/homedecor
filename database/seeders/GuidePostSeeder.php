<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Media;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

/**
 * Long-form guides supplied by the client, one file each in database/data/guides, with photos from
 * the media library placed inside each article.
 * Create-only: an existing slug (even soft-deleted) is skipped so admin edits are never overwritten.
 * Each new guide is placed first in its service's "Further reading" list.
 */
class GuidePostSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        $authorId = User::where('role', 'admin')->orderBy('id')->value('id');

        foreach (self::guides() as $data) {
            if (BlogPost::withTrashed()->where('slug', $data['slug'])->exists()) {
                continue;
            }

            $service = Service::where('slug', $data['service'])->first();

            $post = BlogPost::create([
                'slug' => $data['slug'],
                'title' => $data['title'],
                'excerpt' => $data['excerpt'],
                'body' => $this->withFigures(trim($data['body'])),
                'featured_media_id' => $this->media($data['image']),
                'author_id' => $authorId,
                'service_id' => $service?->id,
                'status' => 'published',
                'published_at' => now()->subMinute(),
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'robots_index' => true,
            ]);

            $post->categories()->sync(BlogCategory::whereIn('slug', $data['categories'])->pluck('id')->all());

            if ($service) {
                $ids = collect([$post->id])->merge($service->articles()->pluck('blog_posts.id'))->unique()->values();
                $service->articles()->sync($ids->mapWithKeys(fn ($id, $i) => [$id => ['sort_order' => $i + 1]])->all());
            }
        }
    }

    /**
     * Re-applies the guide files for these slugs to posts that are already live: title, excerpt,
     * meta, body (with its library photos) and featured photo. Used by data migrations when a
     * batch of guides gets new photos or corrected content.
     *
     * @param  array<int, string>  $slugs
     */
    public function syncExisting(array $slugs): void
    {
        $guides = collect(self::guides())->keyBy('slug');

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->first();
            $data = $guides->get($slug);

            if (! $post || ! $data) {
                continue;
            }

            $post->update([
                'title' => $data['title'],
                'excerpt' => $data['excerpt'],
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'body' => $this->withFigures(trim($data['body'])),
                'featured_media_id' => $this->media($data['image']),
            ]);
        }
    }

    /** Every guide definition (slug, title, excerpt, meta, categories, service, image, body), in file-name order. */
    public static function guides(): array
    {
        $files = glob(database_path('data/guides/*.php'));
        sort($files);

        return array_map(fn ($file) => require $file, $files);
    }

    /** Swaps each <!--figure:slug|caption--> marker for a responsive library image. */
    private function withFigures(string $html): string
    {
        return preg_replace_callback('/<!--figure:([a-z0-9-]+)\|(.+?)-->/', function ($m) {
            $media = Media::findOrFail($this->media($m[1]));
            $src = parse_url($media->url(1200), PHP_URL_PATH);
            $alt = e($media->alt ?: $m[2]);

            return '<figure><img src="'.$src.'" alt="'.$alt.'" width="1200" height="'.$media->heightFor(1200).'" loading="lazy" decoding="async">'
                .'<figcaption>'.e($m[2]).'</figcaption></figure>';
        }, $html);
    }
}
