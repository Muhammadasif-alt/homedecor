<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Services\ImageOptimizer;
use Illuminate\Http\UploadedFile;

class MediaUploadTest extends AdminTestCase
{
    protected function tearDown(): void
    {
        foreach (Media::all() as $media) {
            app(ImageOptimizer::class)->delete($media);
        }
        parent::tearDown();
    }

    public function test_uploading_a_real_jpeg_creates_responsive_variants(): void
    {
        $file = UploadedFile::fake()->createWithContent('Living Room.jpg', $this->jpegContents(1200, 800));

        $this->actingAs($this->editor())
            ->postJson(route('admin.media.store'), ['files' => [$file], 'category' => 'living-room'])
            ->assertCreated()
            ->assertJsonPath('data.0.title', 'Living Room');

        $media = Media::sole();
        $this->assertSame([480, 960, 1200], $media->widths());
        $this->assertSame('living-room', $media->category);
        $this->assertSame(1200, $media->width);
        foreach ($media->filePaths() as $path) {
            $this->assertFileExists($path);
        }
    }

    public function test_jfif_extension_is_accepted(): void
    {
        $file = UploadedFile::fake()->createWithContent('photo.jfif', $this->jpegContents(600, 400));

        $this->actingAs($this->editor())
            ->post(route('admin.media.store'), ['files' => [$file]])
            ->assertRedirect(route('admin.media.index'));

        $this->assertSame(1, Media::count());
    }

    public function test_non_images_are_rejected(): void
    {
        // A fake upload reports its MIME type from the extension, so this exercises the second line
        // of defence: ImageOptimizer's getimagesize() check refuses to process it.
        $file = UploadedFile::fake()->createWithContent('evil.jpg', '<?php echo "hi";');

        $this->actingAs($this->editor())
            ->postJson(route('admin.media.store'), ['files' => [$file]])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('files');

        $this->actingAs($this->editor())
            ->postJson(route('admin.media.store'), ['files' => [UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')]])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('files.0');

        $this->assertSame(0, Media::count());
    }

    public function test_replacing_a_file_keeps_the_same_record(): void
    {
        $editor = $this->editor();
        $this->actingAs($editor)->postJson(route('admin.media.store'), [
            'files' => [UploadedFile::fake()->createWithContent('a.jpg', $this->jpegContents(800, 600))],
        ])->assertCreated();
        $media = Media::sole();
        $oldFiles = $media->filePaths();

        $this->actingAs($editor)->post(route('admin.media.replace', $media), [
            'file' => UploadedFile::fake()->createWithContent('b.jpg', $this->jpegContents(500, 500)),
        ])->assertRedirect(route('admin.media.edit', $media));

        $media->refresh();
        $this->assertSame(1, Media::count());
        $this->assertSame(500, $media->height);
        foreach ($oldFiles as $path) {
            $this->assertFileDoesNotExist($path);
        }
        foreach ($media->filePaths() as $path) {
            $this->assertFileExists($path);
        }
    }

    public function test_picker_feed_returns_json(): void
    {
        $this->actingAs($this->editor())->postJson(route('admin.media.store'), [
            'files' => [UploadedFile::fake()->createWithContent('kitchen.jpg', $this->jpegContents(700, 500))],
            'category' => 'kitchen',
        ]);

        $this->actingAs($this->editor())
            ->getJson(route('admin.media.library', ['category' => 'kitchen', 'q' => 'kitch']))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure(['data' => [['id', 'url', 'alt', 'title', 'category']], 'next_page_url']);
    }
}
