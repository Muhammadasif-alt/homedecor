<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    private const TITLE_SUFFIX = ' | Home Decor Studio';

    /** slug => [name, description, meta_title, meta_description] */
    private const CATEGORIES = [
        'living-room-decor' => [
            'Living Room Decor',
            'Ideas for arranging, layering and styling the room where you relax, gather and entertain.',
            'Living Room Decor Ideas & Inspiration',
            'Living room decor ideas covering layouts, colour palettes, textiles and styling to help you create a welcoming, comfortable everyday space.',
        ],
        'bedroom-decor' => [
            'Bedroom Decor',
            'Guidance on creating calm, restful bedrooms through colour, texture, lighting and layout.',
            'Bedroom Decor Ideas for Restful Spaces',
            'Bedroom decor ideas for calm, restful rooms: headboards, bedding, lighting, colour and storage advice for every size of bedroom.',
        ],
        'wall-decor' => [
            'Wall Decor',
            'Wallpaper, panelling, paint, art and mirrors â€” ways to give every wall character.',
            'Wall Decor, Wallpaper & Panelling Ideas',
            'Wall decor ideas including wallpaper, wall panelling, paint effects, gallery walls and mirrors to add character and depth to any room.',
        ],
        'furniture-styling' => [
            'Furniture Styling',
            'How to choose, arrange and style furniture so rooms feel balanced and easy to live in.',
            'Furniture Styling & Arrangement Tips',
            'Furniture styling tips on choosing pieces, planning layouts and arranging rooms for comfort, flow and a balanced, considered look.',
        ],
        'lighting-ideas' => [
            'Lighting Ideas',
            'Layered lighting, statement fittings and ceiling details that shape the mood of a room.',
            'Lighting Ideas for Every Room',
            'Home lighting ideas: layering ambient, task and accent light, choosing statement fittings and using ceiling details to set the mood.',
        ],
        'small-space-decor' => [
            'Small Space Decor',
            'Clever ideas for making compact rooms feel lighter, larger and better organised.',
            'Small Space Decor Ideas That Work',
            'Small space decor ideas to make compact rooms feel larger and lighter, with smart storage, multi-purpose furniture and colour tricks.',
        ],
        'budget-home-decor' => [
            'Budget Home Decor',
            'Practical ways to refresh your home and make a real difference while spending wisely.',
            'Budget Home Decor Ideas & Refreshes',
            'Budget-friendly home decor ideas: where to spend, where to save and simple refreshes that make a noticeable difference to any room.',
        ],
        'luxury-home-decor' => [
            'Luxury Home Decor',
            'Refined materials, bespoke details and elegant finishes for a more indulgent home.',
            'Luxury Home Decor Ideas & Finishes',
            'Luxury home decor ideas exploring natural stone, rich timbers, bespoke joinery, statement lighting and refined finishing details.',
        ],
        'seasonal-decor' => [
            'Seasonal Decor',
            'Simple ways to adapt your home for the seasons and special occasions throughout the year.',
            'Seasonal Decor Ideas Throughout the Year',
            'Seasonal decor ideas to refresh your home through the year, from lighter summer textiles to warm, layered winter styling and festive touches.',
        ],
        'home-decor-trends' => [
            'Home Decor Trends',
            'The colours, materials and ideas shaping interiors now â€” and how to adopt them thoughtfully.',
            'Home Decor Trends & Interior Ideas',
            'The latest home decor trends in colour, materials and furniture, with advice on adopting them in a way that will last beyond the season.',
        ],
    ];

    public function run(): void
    {
        $order = 0;
        foreach (self::CATEGORIES as $slug => [$name, $description, $metaTitle, $metaDescription]) {
            BlogCategory::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'description' => $description,
                'meta_title' => $metaTitle.self::TITLE_SUFFIX,
                'meta_description' => $metaDescription,
                'sort_order' => ++$order,
            ]);
        }
    }
}
