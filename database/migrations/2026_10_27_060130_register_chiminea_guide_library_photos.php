<?php

use App\Models\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

/**
 * Registers eleven chiminea guide photos (Unsplash License) whose variants are committed in
 * public/media/library, without regenerating the files, so the server copy never drifts from git.
 * Each photo is looked up in database/data/image-manifest.php for its alt text and category.
 */
return new class extends Migration
{
    private const SLUGS = [
        'chiminea-fire-at-dusk',
        'terracotta-chiminea-iron-stand',
        'hand-painted-mexican-pottery',
        'handmade-terracotta-pottery',
        'cast-metal-chiminea-garden',
        'chiminea-on-paving-log-stacks',
        'friends-around-garden-fire',
        'grilled-meat-over-fire',
        'cracked-terracotta-vessel',
        'burning-hardwood-logs',
        'patio-fire-pit-adirondack-chairs',
    ];

    public function up(): void
    {
        $manifest = collect(require database_path('data/image-manifest.php'))->keyBy(1);

        foreach (self::SLUGS as $slug) {
            $path = "media/library/{$slug}";
            $variants = collect([480, 960, 1600, 2400])->filter(fn (int $w) => is_file(public_path("{$path}-{$w}.jpg")))->values();

            if ($variants->isEmpty() || Media::where('path', $path)->exists()) {
                continue;
            }

            $largest = public_path("{$path}-{$variants->last()}.jpg");
            [$width, $height] = getimagesize($largest);
            [$file, , $category, $alt] = $manifest[$slug];

            Media::create([
                'path' => $path,
                'original_name' => "{$file}.jpg",
                'title' => Str::headline($slug),
                'alt' => $alt,
                'category' => $category,
                'width' => $width,
                'height' => $height,
                'size' => filesize($largest),
                'variants' => $variants->all(),
            ]);
        }
    }

    public function down(): void
    {
        Media::whereIn('path', array_map(fn ($slug) => "media/library/{$slug}", self::SLUGS))->delete();
    }
};
