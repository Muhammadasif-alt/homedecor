<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectCategorySeeder extends Seeder
{
    private const CATEGORIES = [
        'Living Rooms',
        'Bedrooms',
        'Kitchens & Dining',
        'Bathrooms',
        'Workspaces & Storage',
        'Full Home',
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $i => $name) {
            ProjectCategory::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'sort_order' => $i + 1]);
        }
    }
}
