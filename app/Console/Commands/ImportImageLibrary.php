<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Services\ImageOptimizer;
use Illuminate\Console\Command;
use Throwable;

/**
 * Imports the original photographs (JPG/JPEG/PNG/WebP/JFIF) from the Image folder into the
 * media library, generating responsive WebP + JPEG variants. Originals are left untouched.
 */
class ImportImageLibrary extends Command
{
    protected $signature = 'images:import {--source= : Folder with original images (default: base_path("Image"))} {--fresh : Delete previously imported library images first}';

    protected $description = 'Convert and import the original Home Decor photographs into the media library';

    public function handle(ImageOptimizer $optimizer): int
    {
        $source = $this->option('source') ?: base_path('Image');
        if (! is_dir($source)) {
            $this->error("Image folder not found: {$source}");

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            Media::where('path', 'like', 'media/library/%')->each(function (Media $media) use ($optimizer) {
                $optimizer->delete($media);
                $media->delete();
            });
        }

        $files = collect(scandir($source))->filter(fn ($f) => preg_match('/\.(jpe?g|jfif|png|webp)$/i', $f))->values();
        $manifest = require database_path('data/image-manifest.php');
        $matched = [];
        $rows = [];

        foreach ($manifest as [$prefix, $slug, $category, $alt]) {
            $file = $files->first(fn ($f) => str_starts_with($f, $prefix));
            if (! $file) {
                $rows[] = [$slug, 'MISSING', "No file starts with \"{$prefix}\""];

                continue;
            }
            $matched[] = $file;

            if (Media::where('path', "media/library/{$slug}")->exists()) {
                $rows[] = [$slug, 'skipped', 'already imported'];

                continue;
            }

            try {
                $media = $optimizer->store("{$source}/{$file}", 'media/library', [
                    'slug' => $slug, 'alt' => $alt, 'category' => $category, 'original_name' => $file,
                ]);
                $rows[] = [$slug, 'ok', "{$media->width}x{$media->height} → ".implode('/', $media->variants).'w'];
            } catch (Throwable $e) {
                $rows[] = [$slug, 'FAILED', $e->getMessage()];
            }
        }

        foreach ($files->diff($matched) as $unmapped) {
            $rows[] = [$unmapped, 'UNMAPPED', 'Add this file to database/data/image-manifest.php'];
        }

        $this->table(['Image', 'Status', 'Detail'], $rows);
        $problems = collect($rows)->whereIn(1, ['MISSING', 'FAILED', 'UNMAPPED'])->count();
        $this->info(count($matched).' of '.$files->count().' files mapped; '.$problems.' problem(s).');

        return $problems ? self::FAILURE : self::SUCCESS;
    }
}
