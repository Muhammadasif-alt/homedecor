<?php

namespace App\Http\Controllers\Admin\Support;

use App\Models\Media;
use Illuminate\Support\Collection;

/**
 * Shapes media records for the admin JS picker: {id, url, alt, title, category}.
 */
class MediaPresenter
{
    public static function item(Media $media): array
    {
        return [
            'id' => $media->id,
            'url' => $media->url(480),
            'alt' => (string) $media->alt,
            'title' => (string) ($media->title ?: $media->original_name),
            'category' => $media->category,
            'width' => $media->width,
            'height' => $media->height,
        ];
    }

    /** Look up several ids at once, keyed by id. */
    public static function lookup(iterable $ids): Collection
    {
        $ids = collect($ids)->filter()->map(fn ($id) => (int) $id)->unique()->values();

        return $ids->isEmpty()
            ? collect()
            : Media::whereIn('id', $ids)->get()->keyBy('id')->map(fn (Media $m) => self::item($m));
    }

    public static function find(mixed $id): ?array
    {
        if ($id instanceof Media) {
            return self::item($id);
        }

        return $id ? self::lookup([$id])->get((int) $id) : null;
    }
}
