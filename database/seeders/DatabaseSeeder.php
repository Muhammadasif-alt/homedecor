<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's content. Requires `php artisan images:import` to have been run first
     * so that the media library exists.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingsSeeder::class,
            NavigationSeeder::class,
            ProjectCategorySeeder::class,
            BlogCategorySeeder::class,
            ServiceSeeder::class,
            FaqSeeder::class,
            ProjectSeeder::class,
            PageSeeder::class,
        ]);

        if (class_exists(BlogPostSeeder::class)) {
            $this->call(BlogPostSeeder::class);
        }

        // Runs last: links the new services to existing projects and articles.
        $this->call([ExtendedServiceSeeder::class, ExtendedBlogPostSeeder::class, GuidePostSeeder::class]);
    }
}
