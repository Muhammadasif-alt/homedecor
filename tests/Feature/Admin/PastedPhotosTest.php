<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Services\ImageOptimizer;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class PastedPhotosTest extends AdminTestCase
{
    /** A public address given as an IP, so the test needs no DNS lookup. */
    private const PHOTO_URL = 'http://93.184.215.14/uploads/garden-bar.jpg';

    protected function setUp(): void
    {
        parent::setUp();
        Media::query()->toBase()->delete();
    }

    protected function tearDown(): void
    {
        foreach (Media::where('path', 'like', 'media/uploads/%')->get() as $media) {
            app(ImageOptimizer::class)->delete($media);
        }
        parent::tearDown();
    }

    public function test_a_pasted_image_address_is_downloaded_into_the_media_library(): void
    {
        Http::fake([self::PHOTO_URL => Http::response($this->jpegContents(1200, 800), 200, ['Content-Type' => 'image/jpeg'])]);

        $this->actingAs($this->editor())
            ->postJson(route('admin.media.import-url'), ['url' => self::PHOTO_URL, 'alt' => 'Outdoor bar at sunset'])
            ->assertCreated()
            ->assertJsonPath('data.alt', 'Outdoor bar at sunset');

        $media = Media::sole();
        $this->assertStringStartsWith('media/uploads/', $media->path);
        $this->assertSame('Outdoor bar at sunset', $media->title);
        $this->assertSame('garden-bar.jpg', $media->original_name);
        foreach ($media->filePaths() as $path) {
            $this->assertFileExists($path);
        }
    }

    public function test_private_and_local_addresses_are_refused(): void
    {
        Http::fake();

        foreach (['http://127.0.0.1/photo.jpg', 'http://192.168.1.10/photo.jpg', 'http://169.254.169.254/latest', 'ftp://93.184.215.14/photo.jpg'] as $url) {
            $this->actingAs($this->editor())->postJson(route('admin.media.import-url'), ['url' => $url])->assertStatus(422);
        }

        Http::assertNothingSent();
        $this->assertSame(0, Media::count());
    }

    public function test_an_address_that_is_not_an_image_is_refused(): void
    {
        Http::fake([self::PHOTO_URL => Http::response('<html>Not found</html>', 200, ['Content-Type' => 'text/html'])]);

        $this->actingAs($this->editor())
            ->postJson(route('admin.media.import-url'), ['url' => self::PHOTO_URL])
            ->assertStatus(422)
            ->assertJsonPath('message', 'That address is not an image.');

        $this->assertSame(0, Media::count());
    }

    public function test_an_unreachable_address_gets_a_plain_message(): void
    {
        Http::fake(fn () => throw new ConnectionException('cURL error 28: timed out'));

        $this->actingAs($this->editor())
            ->postJson(route('admin.media.import-url'), ['url' => self::PHOTO_URL])
            ->assertStatus(422)
            ->assertJsonPath('message', 'The image could not be downloaded from that address. Save it and upload it on the next step.');

        $this->assertSame(0, Media::count());
    }

    public function test_guests_cannot_import_images(): void
    {
        Http::fake();

        $this->post(route('admin.media.import-url'), ['url' => self::PHOTO_URL])->assertRedirect();

        Http::assertNothingSent();
    }

    public function test_pasted_photos_fill_the_image_notes_they_came_from(): void
    {
        $featured = $this->libraryPhoto('bar-at-sunset');
        $inline = $this->libraryPhoto('framing-the-bar');
        $text = "[IMAGE: Bar at sunset]\n\n# Build a Bar\n\nIntro.\n\n## 1. Framing\n\n[IMAGE: Framing the bar]\n\nStuds.\n\n## 2. Seating\n\n[IMAGE: A note typed by hand]\n\nStools.";

        $response = $this->actingAs($this->editor())->post(route('admin.blog-posts.import.preview'), [
            'text' => $text,
            'pasted' => [
                ['media_id' => $featured->id, 'alt' => 'Bar at sunset', 'caption' => ''],
                ['media_id' => $inline->id, 'alt' => 'Framing the bar', 'caption' => 'Studs at 16-inch centres.'],
            ],
        ])->assertOk();

        $prefill = $response->viewData('prefill');
        $this->assertSame($featured->id, $prefill[0]['media']->id);
        $this->assertSame($inline->id, $prefill[1]['media']->id);
        $this->assertSame('Studs at 16-inch centres.', $prefill[1]['caption']);
        $this->assertArrayNotHasKey(2, $prefill, 'A hand-typed note keeps an empty slot.');
        $response->assertSee('value="Studs at 16-inch centres."', false)
            ->assertSee('/media/library/framing-the-bar-1600.jpg', false);
    }

    public function test_a_pasted_photo_that_was_deleted_is_reported(): void
    {
        $this->actingAs($this->editor())->post(route('admin.blog-posts.import.preview'), [
            'text' => "# Title\n\n[IMAGE: Gone]",
            'pasted' => [['media_id' => 999, 'alt' => 'Gone']],
        ])->assertSessionHasErrors('pasted.0.media_id');
    }

    public function test_the_paste_page_loads_the_photo_paste_box(): void
    {
        $this->actingAs($this->editor())->get(route('admin.blog-posts.import'))->assertOk()
            ->assertSee('x-data="articlePaste()"', false)
            ->assertSee('x-on:paste="paste($event)"', false)
            ->assertSee('x-ref="text"', false)
            ->assertSee('Drop photos here, or click to choose them')
            ->assertSee(route('admin.media.import-url'), false);
    }

    private function libraryPhoto(string $slug): Media
    {
        return Media::create([
            'path' => "media/library/{$slug}", 'alt' => $slug, 'category' => 'outdoor',
            'width' => 2400, 'height' => 1800, 'variants' => [480, 960, 1600, 2400],
        ]);
    }
}
