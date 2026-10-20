<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\BuildsSiteContent;
use Tests\TestCase;

class AdSenseTest extends TestCase
{
    use BuildsSiteContent, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->baseSite();
    }

    public function test_ads_txt_is_missing_until_a_publisher_id_is_set(): void
    {
        $this->get('/ads.txt')->assertNotFound();
    }

    public function test_ads_txt_and_script_use_the_normalised_publisher_id(): void
    {
        Setting::put(['adsense_publisher_id' => 'ca-pub-1234567890123456']);

        $this->get('/ads.txt')->assertOk()
            ->assertSeeText('google.com, pub-1234567890123456, DIRECT, f08c47fec0942fa0');

        $this->app['env'] = 'production';
        $this->get('/')->assertOk()
            ->assertSee('adsbygoogle.js?client=ca-pub-1234567890123456', false)
            ->assertSee('<meta name="google-adsense-account" content="ca-pub-1234567890123456">', false);
    }
}
