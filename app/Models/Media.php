<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An optimized image. Files live in public/{path}-{width}.{webp|jpg}.
 */
class Media extends Model
{
    public const CATEGORIES = [
        'living-room' => 'Living Room',
        'bedroom' => 'Bedroom',
        'kitchen' => 'Kitchen',
        'bathroom' => 'Bathroom',
        'wall-decor' => 'Wall Decor',
        'curtains' => 'Curtains',
        'furniture' => 'Furniture',
        'lighting' => 'Lighting',
        'accessories' => 'Accessories',
        'full-home' => 'Full Home',
        'process' => 'Process / Planning',
        'construction' => 'Construction & Finishing',
        'outdoor' => 'Outdoor & Landscape',
        'before-after' => 'Before & After',
        'gallery' => 'Gallery / Portfolio',
    ];

    protected $fillable = [
        'path', 'original_name', 'title', 'alt', 'caption', 'category',
        'width', 'height', 'size', 'variants',
    ];

    protected function casts(): array
    {
        return ['variants' => 'array'];
    }

    public function widths(): array
    {
        $widths = $this->variants ?: [];
        sort($widths);

        return $widths;
    }

    /** URL of the variant closest to (and not smaller than) the requested width. */
    public function url(?int $width = null, string $format = 'jpg'): string
    {
        $widths = $this->widths();
        $chosen = end($widths) ?: 0;
        if ($width) {
            foreach ($widths as $w) {
                if ($w >= $width) {
                    $chosen = $w;
                    break;
                }
            }
        }

        return asset("{$this->path}-{$chosen}.{$format}");
    }

    public function srcset(string $format): string
    {
        return collect($this->widths())
            ->map(fn ($w) => asset("{$this->path}-{$w}.{$format}")." {$w}w")
            ->implode(', ');
    }

    public function heightFor(int $width): int
    {
        return $this->width ? (int) round($width * $this->height / $this->width) : 0;
    }

    public function filePaths(): array
    {
        return collect($this->widths())
            ->flatMap(fn ($w) => [public_path("{$this->path}-{$w}.webp"), public_path("{$this->path}-{$w}.jpg")])
            ->all();
    }
}
