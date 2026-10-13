<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Three services shipped with five styles, leaving a lone card on the second row of the 3-column grid.
 * Adds a sixth style to each, but only while the list is still exactly five long and lacks it,
 * so anything changed in the admin is left untouched.
 */
return new class extends Migration
{
    private const ADDITIONS = [
        'living-room-decoration' => ['Japandi Calm', 'Low-slung furniture, light oak and muted linen tones for a quiet, uncluttered living space.'],
        'wall-decor-wallpaper' => ['Bold Wallpaper', 'Botanical, mural or grasscloth wallpapers that turn a single wall into the focal point.'],
        'lighting-ceiling-decor' => ['Hidden LED Coves', 'Concealed strip lighting in ceilings, shelves and headboards for a soft, glare-free glow.'],
    ];

    public function up(): void
    {
        foreach (self::ADDITIONS as $slug => [$title, $text]) {
            $row = DB::table('services')->where('slug', $slug)->first(['id', 'styles']);
            $styles = json_decode($row->styles ?? '[]', true) ?: [];

            if (count($styles) !== 5 || in_array($title, array_column($styles, 'title'), true)) {
                continue;
            }

            $styles[] = ['title' => $title, 'text' => $text];
            DB::table('services')->where('id', $row->id)->update(['styles' => json_encode($styles)]);
        }
    }

    public function down(): void
    {
        foreach (self::ADDITIONS as $slug => [$title]) {
            $row = DB::table('services')->where('slug', $slug)->first(['id', 'styles']);
            if (! $row) {
                continue;
            }
            $styles = array_values(array_filter(json_decode($row->styles ?? '[]', true) ?: [], fn ($s) => ($s['title'] ?? null) !== $title));
            DB::table('services')->where('id', $row->id)->update(['styles' => json_encode($styles)]);
        }
    }
};
