<?php

namespace Tests\Feature;

use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutdoorKitchenPhotosTest extends TestCase
{
    use RefreshDatabase;

    private function migration(): object
    {
        return require database_path('migrations/2026_10_27_054636_register_outdoor_kitchen_library_photos.php');
    }

    public function test_migration_registers_the_committed_photos_with_their_manifest_alt_text(): void
    {
        $this->migration()->up();

        $media = Media::where('path', 'media/library/outdoor-kitchen-island-bar-stools')->firstOrFail();

        $this->assertSame([480, 960, 1600, 2400], $media->variants);
        $this->assertSame([2400, 1800], [$media->width, $media->height]);
        $this->assertSame('outdoor', $media->category);
        $this->assertStringContainsString('outdoor kitchen', $media->alt);
        $this->assertSame(4, Media::where('path', 'like', 'media/library/%outdoor%kitchen%')->orWhere('path', 'media/library/outdoor-grill-station-garden')->count());
    }

    public function test_migration_does_not_duplicate_existing_rows(): void
    {
        $this->migration()->up();
        $this->migration()->up();

        $this->assertSame(1, Media::where('path', 'media/library/outdoor-grill-station-garden')->count());
    }

    public function test_chiminea_guide_photos_are_registered_from_committed_files(): void
    {
        (require database_path('migrations/2026_10_27_060130_register_chiminea_guide_library_photos.php'))->up();

        $this->assertSame(1, Media::where('path', 'media/library/patio-fire-pit-adirondack-chairs')->count());
        $hero = Media::where('path', 'media/library/chiminea-fire-at-dusk')->firstOrFail();
        $this->assertSame([2400, 1081], [$hero->width, $hero->height]);
    }
}
