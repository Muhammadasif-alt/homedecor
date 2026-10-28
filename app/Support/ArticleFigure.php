<?php

namespace App\Support;

use App\Models\Media;

/**
 * Renders a library photo as the <figure> markup used inside article bodies, so seeded guides and
 * articles pasted in the admin produce identical image HTML.
 */
class ArticleFigure
{
    public const WIDTH = 1200;

    public static function html(Media $media, ?string $caption = null): string
    {
        $src = parse_url($media->url(self::WIDTH), PHP_URL_PATH);
        $alt = e($media->alt ?: (string) $caption);
        $height = $media->heightFor(self::WIDTH);
        $figcaption = filled($caption) ? '<figcaption>'.e($caption).'</figcaption>' : '';

        return '<figure><img src="'.$src.'" alt="'.$alt.'" width="'.self::WIDTH.'" height="'.$height.'" loading="lazy" decoding="async">'
            .$figcaption.'</figure>';
    }
}
