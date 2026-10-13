<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Converts any GD-readable image (JPG, PNG, WebP, JFIF) into responsive WebP + JPEG
 * variants under public/media/. The source file is never modified.
 */
class ImageOptimizer
{
    public const WIDTHS = [480, 960, 1600, 2400];

    private const WEBP_QUALITY = 80;

    private const JPEG_QUALITY = 82;

    /**
     * @param  array{title?:string, alt?:string, caption?:string, category?:string, slug?:string}  $attributes
     */
    public function store(string $sourcePath, string $directory, array $attributes = []): Media
    {
        $image = $this->load($sourcePath);
        [$width, $height] = [imagesx($image), imagesy($image)];

        $slug = Str::slug($attributes['slug'] ?? $attributes['title'] ?? pathinfo($sourcePath, PATHINFO_FILENAME)) ?: 'image';
        $relative = $this->uniquePath(trim($directory, '/'), Str::limit($slug, 80, ''));
        @mkdir(dirname(public_path($relative)), 0755, true);

        $variants = [];
        $largestJpegSize = 0;
        foreach ($this->targetWidths($width) as $targetWidth) {
            $resized = $this->resize($image, $width, $height, $targetWidth);
            $base = public_path("{$relative}-{$targetWidth}");
            imagewebp($resized, "{$base}.webp", self::WEBP_QUALITY);
            imagejpeg($resized, "{$base}.jpg", self::JPEG_QUALITY);
            if ($resized !== $image) {
                imagedestroy($resized);
            }
            $variants[] = $targetWidth;
            $largestJpegSize = filesize("{$base}.jpg");
        }
        imagedestroy($image);

        return Media::create([
            'path' => $relative,
            'original_name' => $attributes['original_name'] ?? basename($sourcePath),
            'title' => $attributes['title'] ?? Str::headline($slug),
            'alt' => $attributes['alt'] ?? null,
            'caption' => $attributes['caption'] ?? null,
            'category' => $attributes['category'] ?? null,
            'width' => $width,
            'height' => $height,
            'size' => $largestJpegSize,
            'variants' => $variants,
        ]);
    }

    public function delete(Media $media): void
    {
        foreach ($media->filePaths() as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /** Validates the file really is an image (magic bytes, not extension) and loads it. */
    private function load(string $path): \GdImage
    {
        $info = @getimagesize($path);
        if (! $info || ! in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            throw new RuntimeException('Unsupported or corrupt image: '.basename($path));
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path), // JFIF is JPEG
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
        };
        if (! $image) {
            throw new RuntimeException('Could not decode image: '.basename($path));
        }

        if ($info[2] === IMAGETYPE_JPEG) {
            $image = $this->applyExifOrientation($image, $path);
        }

        return $this->flattenOnWhite($image);
    }

    private function targetWidths(int $width): array
    {
        $max = max(self::WIDTHS);
        $widths = array_values(array_filter(self::WIDTHS, fn ($w) => $w < $width));
        // Keep a variant at the original width when it is below the largest step.
        if ($width <= $max && ! in_array($width, $widths, true)) {
            $widths[] = $width;
        }

        return $widths ?: [min($width, $max)];
    }

    private function resize(\GdImage $image, int $width, int $height, int $targetWidth): \GdImage
    {
        if ($targetWidth >= $width) {
            return $image;
        }
        $targetHeight = (int) round($height * $targetWidth / $width);
        $resized = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        return $resized;
    }

    private function flattenOnWhite(\GdImage $image): \GdImage
    {
        $flat = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
        imagecopy($flat, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);

        return $flat;
    }

    private function applyExifOrientation(\GdImage $image, string $path): \GdImage
    {
        $orientation = function_exists('exif_read_data') ? (@exif_read_data($path)['Orientation'] ?? 1) : 1;

        return match ((int) $orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    private function uniquePath(string $directory, string $slug): string
    {
        $candidate = "{$directory}/{$slug}";
        $i = 2;
        while (Media::where('path', $candidate)->exists()) {
            $candidate = "{$directory}/{$slug}-".$i++;
        }

        return $candidate;
    }
}
