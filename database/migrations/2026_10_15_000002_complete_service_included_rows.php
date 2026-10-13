<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Five services shipped with five "What's included" items, leaving a lone card on the second row.
 * Adds a sixth item to each, but only while the list is still exactly five long and lacks it,
 * so anything changed in the admin is left untouched.
 */
return new class extends Migration
{
    private const ADDITIONS = [
        'bedroom-decoration' => ['Artwork & final styling', 'Art, mirrors and bedside accessories placed to finish the room in a calm, personal way.'],
        'kitchen-decoration' => ['Blinds & window dressing', 'Easy-clean blinds or café curtains that control light without getting in the way of cooking.'],
        'bathroom-decoration' => ['Storage & organisation', 'Niches, shelves and baskets that keep essentials to hand and countertops clear.'],
        'curtains-window-treatments' => ['Care & aftercare advice', 'Cleaning and care guidance so fabrics, linings and mechanisms stay looking their best.'],
        'lighting-ceiling-decor' => ['Art & shelf lighting', 'Picture lights and shelf lighting that highlight artwork, joinery and displays.'],
    ];

    public function up(): void
    {
        foreach (self::ADDITIONS as $slug => [$title, $text]) {
            $row = DB::table('services')->where('slug', $slug)->first(['id', 'included']);
            $items = json_decode($row->included ?? '[]', true) ?: [];

            if (count($items) !== 5 || in_array($title, array_column($items, 'title'), true)) {
                continue;
            }

            $items[] = ['title' => $title, 'text' => $text];
            DB::table('services')->where('id', $row->id)->update(['included' => json_encode($items)]);
        }
    }

    public function down(): void
    {
        foreach (self::ADDITIONS as $slug => [$title]) {
            $row = DB::table('services')->where('slug', $slug)->first(['id', 'included']);
            if (! $row) {
                continue;
            }
            $items = array_values(array_filter(json_decode($row->included ?? '[]', true) ?: [], fn ($i) => ($i['title'] ?? null) !== $title));
            DB::table('services')->where('id', $row->id)->update(['included' => json_encode($items)]);
        }
    }
};
