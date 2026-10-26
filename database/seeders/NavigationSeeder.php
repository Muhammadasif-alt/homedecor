<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    /** menu => [[label, url, is_button]] */
    private const MENUS = [
        'header' => [
            ['Home', '/', false],
            ['About Us', '/about-us', false],
            ['Services', '/services', false],
            ['Gallery', '/gallery', false],
            ['Blog', '/blog', false],
            ['Contact', '/contact', false],
            ['Get a Quote', '/book-consultation', true],
        ],
        'footer_quick' => [
            ['Our Process', '/our-process', false],
            ['FAQs', '/faqs', false],
            ['Service Areas', '/service-areas', false],
        ],
        'footer_legal' => [
            ['Privacy Policy', '/privacy-policy', false],
            ['Terms & Conditions', '/terms-conditions', false],
            ['Cookie Policy', '/cookie-policy', false],
        ],
    ];

    public function run(): void
    {
        foreach (self::MENUS as $menu => $items) {
            foreach ($items as $i => [$label, $url, $isButton]) {
                NavigationItem::updateOrCreate(
                    ['menu' => $menu, 'url' => $url],
                    ['label' => $label, 'is_button' => $isButton, 'is_enabled' => true, 'sort_order' => $i + 1],
                );
            }
        }
    }
}
