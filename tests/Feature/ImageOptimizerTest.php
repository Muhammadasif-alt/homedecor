<?php

namespace Tests\Feature;

use App\Services\ImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Tests\TestCase;

class ImageOptimizerTest extends TestCase
{
    use RefreshDatabase;

    private string $dir = 'media/testing';

    protected function tearDown(): void
    {
        File::deleteDirectory(public_path($this->dir));
        parent::tearDown();
    }

    public function test_jfif_is_converted_to_responsive_webp_and_jpeg_without_upscaling(): void
    {
        $source = tempnam(sys_get_temp_dir(), 'img').'.jfif';
        $gd = imagecreatetruecolor(1200, 800);
        imagejpeg($gd, $source); // JFIF files are JPEG data
        $originalHash = md5_file($source);

        $media = app(ImageOptimizer::class)->store($source, $this->dir, ['slug' => 'Bright Lounge', 'alt' => 'Bright lounge']);

        $this->assertSame([480, 960, 1200], $media->variants);
        $this->assertSame([1200, 800], [$media->width, $media->height]);
        $this->assertSame("{$this->dir}/bright-lounge", $media->path);
        foreach ($media->filePaths() as $file) {
            $this->assertFileExists($file);
        }
        $this->assertSame('image/webp', mime_content_type(public_path("{$this->dir}/bright-lounge-480.webp")));
        $this->assertSame($originalHash, md5_file($source), 'Original must stay untouched');

        app(ImageOptimizer::class)->delete($media);
        $this->assertFileDoesNotExist(public_path("{$this->dir}/bright-lounge-480.webp"));
    }

    public function test_non_images_are_rejected_even_with_an_image_extension(): void
    {
        $fake = tempnam(sys_get_temp_dir(), 'img').'.jpg';
        file_put_contents($fake, '<?php echo "not an image";');

        $this->expectException(RuntimeException::class);
        app(ImageOptimizer::class)->store($fake, $this->dir);
    }
}
