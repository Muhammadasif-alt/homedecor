<?php

namespace App\Support;

use App\Models\Media;
use Illuminate\Support\Str;

/**
 * Registers library photos whose responsive variants are committed in public/media/library,
 * reading alt text and category from database/data/image-manifest.php. Files are never
 * regenerated, so the server's copies always match git.
 */
class LibraryPhotoRegistrar
{
    private const WIDTHS = [480, 960, 1200, 1600, 2400];

    /** @param  array<int, string>  $slugs */
    public static function register(array $slugs): void
    {
        $manifest = collect(require database_path('data/image-manifest.php'))->keyBy(1);

        foreach ($slugs as $slug) {
            $path = "media/library/{$slug}";
            $variants = collect(self::WIDTHS)->filter(fn (int $width) => is_file(public_path("{$path}-{$width}.jpg")))->values();

            if ($variants->isEmpty() || ! $manifest->has($slug) || Media::where('path', $path)->exists()) {
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
}
