<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

/**
 * Portfolio projects built only from the real photo library. Text describes what is visible in the
 * images (design approach, materials, palette) — no client brief, location or history is invented.
 * No before/after media exists, so those fields stay empty.
 */
class ProjectSeeder extends Seeder
{
    use SeedsMedia;

    private const TITLE_SUFFIX = ' | Home Decor Studio';

    private const PROJECTS = [
        'open-plan-marble-living-kitchen' => [
            'title' => 'Open-Plan Marble Living & Kitchen',
            'category' => 'full-home',
            'featured' => true,
            'summary' => 'A light-filled open-plan ground floor where polished marble, soft neutral seating and a marble kitchen island flow together as one calm, connected space.',
            'overview' => '<p>This open-plan interior brings living, dining and kitchen areas together under a single, restrained palette. Polished marble flooring with a contrasting inlay border runs throughout, giving the space continuity, while a marble feature wall anchors the seating area.</p>',
            'solution' => '<p>Each zone is defined by furniture rather than walls. Low, neutral sofas and a cream sectional form the lounge, a dining table sits between living and cooking areas, and a marble-topped island with bar stools and pendant lights marks the kitchen. Keeping colours soft lets the stone and the natural light take centre stage.</p>',
            'materials' => '<ul><li>Polished marble flooring with dark inlay border</li><li>Marble feature wall and kitchen island</li><li>Neutral upholstered sofas and sectional</li><li>Pendant lighting over island and dining table</li></ul>',
            'cover' => 'marble-floor-living-room',
            'images' => ['living-room-marble-floor-sectional', 'marble-lounge-dining-open-plan', 'open-kitchen-marble-island'],
            'services' => ['full-home-decoration', 'living-room-decoration', 'kitchen-decoration'],
            'meta_title' => 'Open-Plan Marble Living & Kitchen',
            'meta_description' => 'An open-plan marble interior where living, dining and kitchen areas flow together in a calm, neutral palette with statement stone.',
        ],
        'marble-entrance-hall-staircase' => [
            'title' => 'Marble Entrance Hall & Lit Staircase',
            'category' => 'full-home',
            'featured' => true,
            'summary' => 'A welcoming marble foyer and staircase atrium, where lit stone treads, indoor planting and a dark inlay border create a striking first impression.',
            'overview' => '<p>The entrance sequence of this home is treated as a space in its own right. A marble foyer with a dark inlay border leads past a pivot door towards the dining room, while a staircase atrium rises beside generous indoor planting.</p>',
            'solution' => '<p>Concealed lighting beneath each marble tread turns the staircase into a sculptural feature, especially in the evening. Planters soften the expanse of stone and bring life into the atrium, and clear sightlines connect the hall with the living spaces beyond, so the home reveals itself gradually as you enter.</p>',
            'materials' => '<ul><li>Polished marble floors, stairs and inlay detailing</li><li>Integrated LED step lighting</li><li>Large-scale indoor planters</li><li>Full-height pivot entrance door</li></ul>',
            'cover' => 'marble-entrance-hall-staircase',
            'images' => ['marble-foyer-inlay', 'staircase-atrium-indoor-planting'],
            'services' => ['full-home-decoration', 'lighting-ceiling-decor', 'furniture-styling'],
            'meta_title' => 'Marble Entrance Hall & Lit Staircase',
            'meta_description' => 'A marble foyer and staircase atrium with lit stone treads, inlay detailing and indoor planting that create a striking welcome.',
        ],
        'walnut-stone-garden-study' => [
            'title' => 'Walnut & Stone Garden Study',
            'category' => 'workspaces-storage',
            'featured' => true,
            'summary' => 'A calm, focused home study wrapped in walnut joinery and natural stone, opening onto the garden through floor-to-ceiling glazing.',
            'overview' => '<p>This study is designed as a quiet retreat for work and reading. Walnut built-in shelving with integrated lighting lines the walls, while a full-height glazed wall frames views of the garden and fills the room with daylight.</p>',
            'solution' => '<p>A walnut and stone desk sits facing the garden, with a leather armchair positioned for reading. A stone-clad column and lit niches add texture and display space, and the warm timber tones balance the cooler stone to keep the room inviting rather than formal.</p>',
            'materials' => '<ul><li>Walnut built-in shelving and desk</li><li>Natural stone desk top and column cladding</li><li>Integrated shelf and niche lighting</li><li>Leather armchair</li></ul>',
            'cover' => 'walnut-study-garden-view',
            'images' => ['study-stone-desk-garden', 'study-walnut-shelving-armchair', 'study-stone-column-garden'],
            'services' => ['furniture-styling', 'lighting-ceiling-decor', 'wall-decor-wallpaper'],
            'meta_title' => 'Walnut & Stone Garden Study',
            'meta_description' => 'A home study with walnut joinery, a stone desk and lit niches, opening onto the garden through floor-to-ceiling glazing.',
        ],
        'oak-marble-walk-in-dressing-room' => [
            'title' => 'Oak & Marble Walk-in Dressing Room',
            'category' => 'workspaces-storage',
            'featured' => true,
            'summary' => 'A light, boutique-style dressing room with oak shelving, a marble-topped island, a window seat and soft pendant lighting.',
            'overview' => '<p>This walk-in dressing room is arranged around a central island, with open oak shelving and lit wardrobes lining the walls. A window seat and garden views keep the room bright and relaxed.</p>',
            'solution' => '<p>The marble-topped island provides storage and a surface for folding and display, while pendants and a chandelier add a decorative layer above. A vanity desk, round mirror, bench and armchair turn the space into somewhere to linger rather than simply store clothes.</p>',
            'materials' => '<ul><li>Oak open shelving and wardrobes with integrated lighting</li><li>Marble-topped central island</li><li>Pendant lights and chandelier</li><li>Upholstered window seat, bench and armchair</li></ul>',
            'cover' => 'walk-in-closet-island',
            'images' => ['walk-in-closet-pendants', 'walk-in-closet-window-seat', 'walk-in-closet-garden-view', 'dressing-room-chandelier'],
            'services' => ['furniture-styling', 'bedroom-decoration', 'lighting-ceiling-decor'],
            'meta_title' => 'Oak & Marble Walk-in Dressing Room',
            'meta_description' => 'A boutique-style dressing room with oak shelving, a marble-topped island, window seat and soft pendant lighting.',
        ],
        'walnut-marble-feature-wall-living-rooms' => [
            'title' => 'Walnut, Marble & Stone Feature Walls',
            'category' => 'living-rooms',
            'featured' => true,
            'summary' => 'Living room schemes built around a statement wall — walnut panelling, book-matched marble, stone and gold inlay — balanced with low, neutral seating.',
            'overview' => '<p>This collection of living room schemes explores how a single feature wall can define a room. Each uses natural materials — walnut, marble or stone — as the backdrop for media walls, shelving and artwork.</p>',
            'solution' => '<p>Seating is kept low and neutral so the walls remain the focus. Integrated shelving and concealed lighting add depth, while herringbone floors, round coffee tables and textured rugs bring warmth and soften the strong architectural lines.</p>',
            'materials' => '<ul><li>Walnut wall panelling and media units</li><li>Book-matched marble and natural stone feature walls</li><li>Gold geometric inlay detailing</li><li>Low modular and sectional seating</li></ul>',
            'cover' => 'walnut-stone-living-room',
            'images' => ['walnut-tv-wall-living-room', 'marble-tv-wall-living-room', 'gold-geometric-wall-panels', 'timber-panelled-living-room-art'],
            'services' => ['wall-decor-wallpaper', 'living-room-decoration', 'furniture-styling'],
            'meta_title' => 'Walnut, Marble & Stone Feature Walls',
            'meta_description' => 'Living room schemes built around statement walls in walnut, book-matched marble and stone, paired with low, neutral seating.',
        ],
        'chandelier-lit-luxury-bedrooms' => [
            'title' => 'Chandelier-Lit Luxury Bedrooms',
            'category' => 'bedrooms',
            'featured' => true,
            'summary' => 'Elegant bedroom schemes with tufted and channelled headboards, statement chandeliers, symmetrical bedside arrangements and refined metallic accents.',
            'overview' => '<p>These bedroom schemes share a sense of quiet glamour. Each is anchored by a tailored upholstered headboard or panelled wall and crowned with a statement light, from crystal chandeliers to sculptural rings.</p>',
            'solution' => '<p>Symmetry brings calm: matching bedside tables, lamps and drapes frame the bed, while a restrained palette of beige, ivory and soft grey lets textures and lighting provide the drama. Gold, bronze and marble accents add polish without overwhelming the restful mood.</p>',
            'materials' => '<ul><li>Tufted, channelled and panelled upholstered headboards</li><li>Crystal, glass and ring chandeliers</li><li>Marble and bronze bedside tables</li><li>Floor-length drapes</li></ul>',
            'cover' => 'classic-luxury-bedroom-chandelier',
            'images' => ['bedroom-ring-chandelier-tufted-headboard', 'tufted-wall-crystal-chandelier-bedroom', 'symmetrical-panelled-bedroom', 'bedroom-panelled-chandelier', 'upholstered-bed-gold-accents'],
            'services' => ['bedroom-decoration', 'lighting-ceiling-decor', 'wall-decor-wallpaper'],
            'meta_title' => 'Chandelier-Lit Luxury Bedrooms',
            'meta_description' => 'Luxury bedroom schemes with tufted headboards, statement chandeliers, symmetrical bedside styling and refined metallic accents.',
        ],
        'soft-neutral-bedrooms-cove-lighting' => [
            'title' => 'Soft Neutral Bedrooms with Cove Lighting',
            'category' => 'bedrooms',
            'featured' => false,
            'summary' => 'Serene bedroom schemes in warm neutrals, with backlit panelled headboards, cove-lit ceilings, globe pendants and sheer curtains.',
            'overview' => '<p>These bedroom schemes focus on softness and calm. Pale, warm neutrals, upholstered beds and full-height panelled headboard walls create a cocooning atmosphere.</p>',
            'solution' => '<p>Lighting is largely indirect — cove ceilings and backlit headboards wash the walls in a gentle glow, supported by pendant lights at the bedside. Sheer curtains diffuse daylight, and a fluted feature wall with a sculptural light adds a contemporary note.</p>',
            'materials' => '<ul><li>Upholstered and panelled headboard walls</li><li>Cove ceiling and backlit headboard lighting</li><li>Globe and sculptural pendant lights</li><li>Sheer curtains and soft bedding layers</li></ul>',
            'cover' => 'bedroom-panelled-headboard-wall',
            'images' => ['bedroom-panelled-headboard-cove-light', 'bedroom-lit-headboard', 'bedroom-pendant-lights-cove-ceiling', 'bedroom-fluted-wall-light-sculpture'],
            'services' => ['bedroom-decoration', 'lighting-ceiling-decor', 'curtains-window-treatments'],
            'meta_title' => 'Soft Neutral Bedrooms, Cove Lighting',
            'meta_description' => 'Serene bedrooms in warm neutrals with backlit panelled headboards, cove-lit ceilings, globe pendants and soft sheer curtains.',
        ],
        'white-walnut-kitchens' => [
            'title' => 'Handleless White & Walnut Kitchens',
            'category' => 'kitchens-dining',
            'featured' => false,
            'summary' => 'Kitchen schemes that pair crisp handleless cabinetry and marble with warm walnut islands, open shelving, greenery and statement pendants.',
            'overview' => '<p>These kitchen schemes balance clean, practical surfaces with warmth. Handleless white and grey cabinetry and marble splashbacks keep lines calm, while timber and greenery add character.</p>',
            'solution' => '<p>Islands act as the social centre of each kitchen, paired with bar stools and highlighted by pendant lighting. Open timber shelving offers a place for ceramics and plants, softening the cabinetry and making the kitchen feel lived-in.</p>',
            'materials' => '<ul><li>Handleless white and grey cabinetry</li><li>Marble splashbacks and worktops</li><li>Walnut island and timber open shelving</li><li>Globe and black metal pendants; upholstered and timber stools</li></ul>',
            'cover' => 'white-kitchen-island-bar-stools',
            'images' => ['white-handleless-kitchen', 'walnut-kitchen-island-pendants', 'kitchen-open-shelving-plants'],
            'services' => ['kitchen-decoration', 'lighting-ceiling-decor', 'home-accessories-styling'],
            'meta_title' => 'Handleless White & Walnut Kitchens',
            'meta_description' => 'Kitchen schemes pairing handleless cabinetry and marble with walnut islands, open shelving, greenery and statement pendants.',
        ],
        'statement-lit-dining-rooms' => [
            'title' => 'Statement-Lit Dining Rooms',
            'category' => 'kitchens-dining',
            'featured' => false,
            'summary' => 'Dining rooms defined by their lighting — twin crystal chandeliers, glass bubble fittings, bronze clusters and ring pendants — over tables dressed for gathering.',
            'overview' => '<p>This collection of dining room schemes shows how lighting can set the character of a room. From formal lacquered spaces to relaxed timber-panelled rooms, the fitting above the table is always the focal point.</p>',
            'solution' => '<p>Each light is scaled to its table: twin chandeliers for a long formal table, a cluster of pendants for a round one, a ring fitting for an oak table. Upholstered chairs, floor-length drapes and warm cove lighting complete the atmosphere for long, comfortable meals.</p>',
            'materials' => '<ul><li>Crystal, glass bubble and bronze pendant fittings</li><li>Oak and lacquered dining tables</li><li>Upholstered dining chairs</li><li>Walnut panelling and floor-length drapes</li></ul>',
            'cover' => 'luxury-dining-crystal-chandeliers',
            'images' => ['dining-room-crystal-chandelier', 'dining-room-bubble-chandelier', 'dining-room-pendant-cluster', 'dining-room-ring-pendants', 'classic-dining-room-drapes'],
            'services' => ['lighting-ceiling-decor', 'kitchen-decoration', 'curtains-window-treatments'],
            'meta_title' => 'Statement-Lit Dining Rooms',
            'meta_description' => 'Dining room schemes defined by statement lighting — crystal chandeliers, glass bubble fittings and pendant clusters over dressed tables.',
        ],
        'warm-timber-stone-bathrooms' => [
            'title' => 'Warm Timber & Stone Bathrooms',
            'category' => 'bathrooms',
            'featured' => false,
            'summary' => 'Spa-inspired bathrooms combining timber vanities, round and backlit mirrors, freestanding tubs, brass details and natural textures.',
            'overview' => '<p>These bathroom schemes aim for a calm, spa-like feel. Warm timber vanities sit against pale stone-toned surfaces, while round mirrors soften the lines of each room.</p>',
            'solution' => '<p>Double vanities and freestanding tubs give larger bathrooms a sense of luxury, while a compact oak vanity with a vessel basin and brass wall lights shows the same warmth at a smaller scale. Backlit mirrors and niche lighting provide gentle, flattering light, and plants add freshness.</p>',
            'materials' => '<ul><li>Oak and walnut vanities</li><li>Round, framed and backlit mirrors</li><li>Freestanding bath and vessel basins</li><li>Brass wall lights and fittings</li></ul>',
            'cover' => 'bathroom-freestanding-tub',
            'images' => ['double-vanity-bathroom-wood', 'oak-vanity-round-mirror', 'backlit-round-mirror-bathroom'],
            'services' => ['bathroom-decoration', 'lighting-ceiling-decor'],
            'meta_title' => 'Warm Timber & Stone Bathrooms',
            'meta_description' => 'Spa-inspired bathrooms with timber vanities, round and backlit mirrors, freestanding tubs, brass details and natural textures.',
        ],
        'lit-shelving-home-offices' => [
            'title' => 'Home Offices with Lit Shelving',
            'category' => 'workspaces-storage',
            'featured' => false,
            'summary' => 'Focused, elegant home offices where backlit shelving, built-in desks and leather seating create workspaces that feel as considered as any living room.',
            'overview' => '<p>These home office schemes treat the workspace as part of the home rather than an afterthought. Built-in shelving with integrated lighting provides display and storage, and acts as a warm backdrop for video calls.</p>',
            'solution' => '<p>Desks are positioned to make the most of natural light, often beside a window or garden view. Palettes range from crisp white with marble and black accents to warm taupe with walnut and leather, showing how the same principles adapt to different tastes.</p>',
            'materials' => '<ul><li>Backlit timber and white lacquer shelving</li><li>Built-in and freestanding desks, including marble tops</li><li>Leather and upholstered task chairs</li><li>Taupe and white wall finishes</li></ul>',
            'cover' => 'white-home-office-shelving',
            'images' => ['backlit-shelving-home-office', 'home-office-lit-shelves', 'home-office-taupe-walls', 'classic-white-study'],
            'services' => ['furniture-styling', 'lighting-ceiling-decor', 'home-accessories-styling'],
            'meta_title' => 'Home Offices with Lit Shelving',
            'meta_description' => 'Home office schemes with backlit shelving, built-in desks and leather seating — workspaces as considered as any living room.',
        ],
        'warm-minimalist-living-spaces' => [
            'title' => 'Warm Minimalist Living Spaces',
            'category' => 'living-rooms',
            'featured' => false,
            'summary' => 'Pared-back living rooms in cream and beige, with low sofas, timber and stone tables, sheer curtains and soft daylight.',
            'overview' => '<p>These living room schemes show how minimalism can feel warm rather than stark. Low, deep sofas in cream and beige, natural timber and soft daylight create a relaxed, uncluttered atmosphere.</p>',
            'solution' => '<p>Furniture is kept low to emphasise the windows and views. Live-edge and walnut coffee tables add organic character, sheer curtains filter the light, and fireplaces provide a quiet focal point. Accessories are limited to a few tactile pieces so the rooms stay calm.</p>',
            'materials' => '<ul><li>Low cream and beige sectional sofas</li><li>Walnut, live-edge timber and nesting tables</li><li>Layered sheer curtains</li><li>Plaster and stone fireplace surrounds</li></ul>',
            'cover' => 'warm-minimal-living-room-sunset',
            'images' => ['minimal-beige-living-room', 'open-plan-minimal-living-room', 'minimal-living-room-fireplace', 'sunlit-sofa-sheer-curtains'],
            'services' => ['living-room-decoration', 'curtains-window-treatments', 'home-accessories-styling'],
            'meta_title' => 'Warm Minimalist Living Spaces',
            'meta_description' => 'Pared-back living rooms in cream and beige with low sofas, timber tables, sheer curtains and soft daylight for a calm, warm feel.',
        ],
    ];

    public function run(): void
    {
        $categories = ProjectCategory::pluck('id', 'slug');
        $services = Service::pluck('id', 'slug');
        $order = 0;

        foreach (self::PROJECTS as $slug => $data) {
            $project = Project::withTrashed()->updateOrCreate(['slug' => $slug], [
                'title' => $data['title'],
                'project_category_id' => $categories[$data['category']],
                'location' => null,
                'summary' => $data['summary'],
                'overview' => $data['overview'],
                'requirements' => null,
                'solution' => $data['solution'],
                'materials' => $data['materials'],
                'cover_media_id' => $this->media($data['cover']),
                'before_media_id' => null,
                'after_media_id' => null,
                'meta_title' => $data['meta_title'].self::TITLE_SUFFIX,
                'meta_description' => $data['meta_description'],
                'is_featured' => $data['featured'],
                'is_published' => true,
                'sort_order' => ++$order,
                'deleted_at' => null,
            ]);

            $this->syncImages($project, $data['images']);
            $project->services()->sync(array_map(fn ($s) => $services[$s], $data['services']));
        }
    }

    private function syncImages(Project $project, array $slugs): void
    {
        $project->images()->delete();

        foreach (array_values($slugs) as $i => $slug) {
            $project->images()->create(['media_id' => $this->media($slug), 'sort_order' => $i + 1]);
        }
    }
}
