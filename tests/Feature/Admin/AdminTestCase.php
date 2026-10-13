<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    protected function admin(array $attributes = []): User
    {
        return User::factory()->create($attributes + ['role' => 'admin', 'is_active' => true]);
    }

    protected function editor(array $attributes = []): User
    {
        return User::factory()->create($attributes + ['role' => 'editor', 'is_active' => true]);
    }

    /** A real JPEG generated with GD. */
    protected function jpegContents(int $width = 1200, int $height = 800): string
    {
        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, imagecolorallocate($image, 199, 181, 156));
        imagefilledrectangle($image, 100, 100, 600, 500, imagecolorallocate($image, 118, 98, 77));
        ob_start();
        imagejpeg($image, null, 85);
        imagedestroy($image);

        return ob_get_clean();
    }
}
