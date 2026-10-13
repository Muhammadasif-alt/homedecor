<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Media;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

/**
 * Long-form guides supplied by the client, with photos from the media library placed inside each article.
 * Create-only: an existing slug (even soft-deleted) is skipped so admin edits are never overwritten.
 * Each new guide is placed first in its service's "Further reading" list.
 */
class GuidePostSeeder extends Seeder
{
    use SeedsMedia;

    public function run(): void
    {
        $authorId = User::where('role', 'admin')->orderBy('id')->value('id');

        foreach ($this->posts() as $data) {
            if (BlogPost::withTrashed()->where('slug', $data['slug'])->exists()) {
                continue;
            }

            $service = Service::where('slug', $data['service'])->first();

            $post = BlogPost::create([
                'slug' => $data['slug'],
                'title' => $data['title'],
                'excerpt' => $data['excerpt'],
                'body' => $this->withFigures(trim($data['body'])),
                'featured_media_id' => $this->media($data['image']),
                'author_id' => $authorId,
                'service_id' => $service?->id,
                'status' => 'published',
                'published_at' => now()->setTime(9, 0),
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'robots_index' => true,
            ]);

            $post->categories()->sync(BlogCategory::whereIn('slug', $data['categories'])->pluck('id')->all());

            if ($service) {
                $ids = collect([$post->id])->merge($service->articles()->pluck('blog_posts.id'))->unique()->values();
                $service->articles()->sync($ids->mapWithKeys(fn ($id, $i) => [$id => ['sort_order' => $i + 1]])->all());
            }
        }
    }

    /** Each entry: slug, title, excerpt, meta, categories, related service, featured image slug and HTML body. */
    private function posts(): array
    {
        return [
            [
                'slug' => 'living-room-colour-scheme-guide',
                'title' => 'The Ultimate Guide to Choosing a Living Room Colour Scheme',
                'excerpt' => 'Neutrals, jewel tones, coastal blues, earthy greens, greys, terracotta and pastels: how each palette changes the mood of a living room, and the rules that make any scheme work.',
                'meta_title' => 'Living Room Colour Scheme Guide: 7 Palettes | Home Decor Studio',
                'meta_description' => 'How to choose a living room colour scheme: seven palettes from warm neutrals to jewel tones, plus the 60-30-10 rule, LRV, lighting and paint finishes.',
                'categories' => ['living-room-decor', 'wall-decor'],
                'service' => 'living-room-decoration',
                'image' => 'sunlit-living-room-sectional',
                'body' => $this->colourSchemeBody(),
            ],
            [
                'slug' => 'small-living-room-decorating-ideas',
                'title' => 'Mastering the Art of Small Living Room Decoration',
                'excerpt' => 'Twelve designer techniques for small living rooms, from vertical storage and see-through furniture to mirrors, layered lighting, scale, texture and plants.',
                'meta_title' => 'Small Living Room Decorating Ideas: 12 Tips | Home Decor Studio',
                'meta_description' => 'Twelve ways to make a small living room feel bigger: vertical storage, glass and acrylic furniture, mirrors, layered lighting, rugs, curtains and plants.',
                'categories' => ['living-room-decor', 'small-space-decor'],
                'service' => 'small-space-apartment-decoration',
                'image' => 'living-room-shelves-paper-lanterns',
                'body' => $this->smallLivingRoomBody(),
            ],
        ];
    }

    /** Swaps each <!--figure:slug|caption--> marker for a responsive library image. */
    private function withFigures(string $html): string
    {
        return preg_replace_callback('/<!--figure:([a-z0-9-]+)\|(.+?)-->/', function ($m) {
            $media = Media::findOrFail($this->media($m[1]));
            $src = parse_url($media->url(1200), PHP_URL_PATH);
            $alt = e($media->alt ?: $m[2]);

            return '<figure><img src="'.$src.'" alt="'.$alt.'" width="1200" height="'.$media->heightFor(1200).'" loading="lazy" decoding="async">'
                .'<figcaption>'.e($m[2]).'</figcaption></figure>';
        }, $html);
    }

    private function colourSchemeBody(): string
    {
        return <<<'HTML'
<p>The living room is the social centre of the home. It is where we host guests, unwind after a long day and express our personal style. That makes its colour scheme one of the most important decisions in interior design. Colour does more than cover a surface: it sets the mood, changes how big the room feels and pulls different pieces of furniture together into one story.</p>
<p>Choosing the right palette takes a balance of instinct and know-how. This guide walks through seven palettes that work in living rooms, then the technical points (proportion, light and paint finish) that decide whether a scheme succeeds.</p>

<h2 id="neutral-palettes">1. Neutral palettes</h2>
<!--figure:minimal-beige-living-room|Layered beige and timber tones keep a neutral living room calm without feeling flat.-->
<p>Neutral colour schemes are timeless, offering calm and architectural clarity. Neutrals are not boring: a well-executed neutral palette relies on a careful interplay of undertones and textures. Start by deciding whether your base is warm (yellow, pink or peach undertones) or cool (blue, green or violet undertones).</p>
<p>A warm neutral scheme of oatmeal, biscuit and taupe creates an inviting, cosy atmosphere, and works especially well in larger rooms that might otherwise feel cold. Cool neutrals such as silver, flint and crisp white give a sharp, gallery-like backdrop that shows off clean lines and modern shapes. To stop a neutral room feeling flat, layer textures (chunky knits, smooth marble, grained wood) to add depth in place of bold colour.</p>

<h2 id="jewel-tones">2. Jewel tones</h2>
<p>For drama and opulence, jewel tones are hard to beat. Inspired by precious stones (emerald, sapphire, ruby and amethyst), these colours are highly saturated and carry real visual weight. A jewel-toned living room feels intimate, enveloping and decidedly high-end.</p>
<p>The secret is balance. If you paint the walls deep emerald, offset the weight with metallic accents like brass or gold, which reflect light and stop the room feeling cave-like. Jewel tones also pair beautifully with dark woods and velvet upholstery. Lighting matters here: warm, layered light from lamps and wall lights, rather than a single harsh ceiling light, brings out the glow of jewel colours and makes the room feel cosy and grand in the evening.</p>

<h2 id="coastal-blues">3. Coastal blues</h2>
<p>Blue is one of the most popular colours for interiors, partly because so many people find it calming. A coastal blue scheme recalls the sea and the open sky, moving from pale duck-egg through to deeper navy accents, grounded by crisp whites and sandy beiges.</p>
<p>Think about which way the room faces. In north-facing rooms blue can look chilly, so choose blues with a slight green or red undertone to add warmth. In sunny, south-facing rooms a cool aqua or sky blue gives a refreshing contrast. The scheme works best with natural materials like jute, linen and light oak, which echo the textures of the shoreline.</p>

<h2 id="earthy-greens">4. Earthy greens</h2>
<!--figure:living-room-sculpture-statement-art|A green sofa brings colour to a stone and marble living room without overwhelming it.-->
<p>With the growing interest in biophilic design, earthy greens have become a staple of modern living rooms. Green links indoors and outdoors and suggests growth and renewal. From soft sage and olive to deep forest green, the palette is remarkably versatile.</p>
<p>Earthy greens behave like a new neutral: easy on the eye and happy alongside almost any wood tone. For a more sophisticated scheme, mix several shades from the same family. A sage wall gives a soft backdrop, while moss velvet cushions or a dark olive accent chair add contrast. Houseplants complete the look, adding real green texture that changes with the light.</p>

<h2 id="monochromatic-greys">5. Monochromatic greys</h2>
<!--figure:living-room-round-coffee-tables|Grey sofas against darker accents show how value contrast keeps a grey scheme from blending into one tone.-->
<p>A monochromatic grey scheme is a hallmark of urban sophistication. Using different tints and shades of a single hue creates a look that is cohesive and highly structured. The key is value contrast: the difference between the lightest and darkest elements in the room.</p>
<p>If your walls are a mid-tone charcoal, choose a sofa that is clearly lighter (mist) or darker (slate) so the furniture does not disappear into the walls. Grey can read as blue, purple or even green depending on the light, so always test grey paint samples at different times of day to check the hidden undertones. To keep the room from feeling sterile, add matte black and polished chrome for a modern edge.</p>

<h2 id="warm-terracotta">6. Warm terracotta</h2>
<!--figure:living-room-curved-sofa-sculptural-chair|A rust bouclé chair is an easy way to bring terracotta warmth into a cream scheme.-->
<p>Baked earth tones like terracotta, sienna and ochre bring immediate warmth and energy to a living room. Inspired by natural clay and Mediterranean landscapes, the palette makes a space feel grounded and welcoming.</p>
<p>Terracotta looks its best in rooms with plenty of natural light, which intensifies its warmth. Because these colours are vibrant, pair them with grounding neutrals like cream, sand or charcoal. A terracotta accent wall, or large ceramics and textiles in the same tones, can turn a cold room into a glowing retreat. The palette also suits handmade pieces such as pottery, woven wall hangings and kilim rugs.</p>

<h2 id="pastel-hues">7. Pastel hues</h2>
<p>Pastels have moved well beyond the nursery. Modern pastel schemes use softened, desaturated colours to create a room that feels bright, cheerful and spacious.</p>
<p>A dusty palette, such as muted pink with sage or lavender, gives a soft, contemporary feel. To keep pastels from looking too sweet, add anchoring elements: black metal hardware, dark wooden furniture legs or a crisp geometric rug give the visual weight needed to balance the lightness. Pastels also suit smaller living rooms, because their high light reflectance helps the walls recede and the space feel larger.</p>

<h2 id="technical-considerations">Technical considerations for your colour scheme</h2>
<p>Beyond the colours themselves, a few technical points decide how well your scheme works:</p>
<ul>
<li><strong>The 60-30-10 rule:</strong> a classic way to balance a palette. Around 60% of the room is the dominant colour (usually walls and rug), 30% the secondary colour (upholstery) and 10% the accent (cushions, artwork and accessories).</li>
<li><strong>Light reflectance value (LRV):</strong> every paint colour has an LRV from 0 (black) to 100 (pure white). If your living room is short on windows, choose colours with a higher LRV to bounce the light you have around the room.</li>
<li><strong>The effect of daylight:</strong> north-facing light is cool and bluish and can make colours look flatter; south-facing light is warm and can make them look more intense. View paint samples in the room itself, morning and evening, before you commit.</li>
<li><strong>Finishes and sheens:</strong> a matte finish absorbs light and hides imperfections, making colours look richer and deeper. Satin or eggshell has a slight sheen, reflects more light and is easier to clean, which makes it practical for busy living rooms.</li>
</ul>
<blockquote><p><strong>Tip:</strong> Paint large samples (at least A4 size) on two different walls and live with them for a few days. Small swatches rarely show how a colour behaves across a whole room.</p></blockquote>

<h2 id="bring-it-together">Bringing it together</h2>
<p>Consider these points alongside a palette that suits your personal style, and you will have a living room that looks beautiful and reflects how you live. Whether you choose the quiet elegance of neutrals or the bold statement of jewel tones, the right colour scheme is the foundation of a beautiful home.</p>
<p>For more ideas, see our <a href="/blog/modern-living-room-decorating-ideas">modern living room decorating ideas</a>, compare <a href="/blog/wallpaper-vs-paint">wallpaper and paint</a>, or read <a href="/blog/how-to-choose-curtains-for-your-living-room">how to choose curtains for your living room</a>. If you would like help choosing a palette, our <a href="/services/living-room-decoration">living room decoration</a> service covers colour, layout, lighting and styling, or you can <a href="/book-consultation">book a consultation</a>.</p>
HTML;
    }

    private function smallLivingRoomBody(): string
    {
        return <<<'HTML'
<p>More and more of us live in compact homes, and turning a small living room into a stylish, comfortable retreat has become a central challenge of modern interior design. A small living room is not a limitation. It is an opportunity to be precise, intentional and creative. By focusing on light, scale and function, even a modest room can feel both spacious and intimate.</p>
<p>The key is balancing looks with practicality. Forget big-room thinking, where more is better, and move to a curated approach in which every piece of furniture, every colour and every accessory has a job to do. This guide covers twelve techniques designers use to make the most of every square metre without giving up elegance.</p>

<h2 id="verticality">1. Verticality</h2>
<p>When floor space is scarce, the most underused asset is the wall. Designers call this drawing the eye upward. Using the full height of a wall distracts from a narrow floor: tall, slim bookshelves that reach the ceiling, or a gallery wall that climbs towards it, make the room feel taller than it is. Vertical storage also gives you room to organise without blocking walkways. Try floating shelves high above a doorway, or a tall, narrow cabinet that stores a lot on a small footprint.</p>

<h2 id="transparency">2. Transparency</h2>
<p>Visual weight matters in a small room. Heavy, solid furniture can anchor a space so firmly that it feels crowded. Decorators often turn to clear or translucent materials such as acrylic and glass instead. An acrylic coffee table or ghost chairs do their job while almost disappearing, because they do not block the line of sight. The floor keeps reading as one open surface, and the room's architecture and floor textures can show through.</p>

<h2 id="multifunctionality">3. Multifunctionality</h2>
<!--figure:coffee-table-styling-tray|A tray turns an ottoman or low table into a usable surface that can be cleared in seconds when you need the space.-->
<p>In a small living room every item has to earn its place, and single-use furniture is a luxury. Look for pieces that do two or three jobs. A large, sturdy ottoman can be a coffee table when topped with a tray, a footrest, or extra seating for guests. Nesting tables tuck away when not in use and come out for entertaining. If you often host overnight visitors, a good sleeper sofa or daybed gives you a seating area and a guest room in one. Pieces with hidden storage, such as lift-top benches or coffee tables with drawers, keep everyday clutter out of sight and the room calm.</p>

<h2 id="reflection">4. Reflection</h2>
<!--figure:living-room-round-mirror-cream-sofa|A large mirror above the sofa bounces light around a cream living room and adds depth.-->
<p>Mirrors are one of the most effective ways to create a sense of space. A well-placed mirror adds visual depth and spreads natural light. The best position is opposite a window, where it reflects the view and the daylight and acts almost like a second window. A large floor mirror leaning against the wall looks relaxed and makes the ceiling feel higher. For a more architectural look, try mirror-fronted cabinets or a group of smaller mirrors arranged as a focal point.</p>

<h2 id="illumination">5. Illumination</h2>
<!--figure:living-room-pendant-cluster-slats|A pendant cluster adds a layer of light above head height without using any floor or table space.-->
<p>Good lighting stops a small room from feeling like a cave. A single overhead light casts harsh shadows that swallow the corners and shrink the room. Instead, layer three kinds of light: ambient for overall glow, task for specific activities (such as a slim floor lamp beside a reading chair) and accent for depth and drama (such as wall lights or LED strips behind the TV). Slim lamps and wall-mounted fittings save table and floor space while making sure every corner is well lit. Our <a href="/blog/layered-lighting-room-by-room-guide">layered lighting guide</a> goes into more detail.</p>

<h2 id="scale">6. Scale</h2>
<!--figure:compact-apartment-living-dining|One generous corner sofa makes better use of a compact living room than several small chairs.-->
<p>A common mistake is choosing miniature furniture. Lots of small pieces make a room feel busy. Surprisingly, one or two larger pieces, such as a generous sectional that fits the room exactly, can make the space feel more substantial and luxurious. Match the scale of the furniture to the room without overwhelming it. Avoid bulky arms and heavy skirts and look for furniture raised on legs: tapered legs, typical of mid-century design, let you see the floor underneath, which makes the room feel more open.</p>

<h2 id="neutrals">7. Neutrals</h2>
<!--figure:warm-minimal-living-room-sunset|Cream, walnut and soft white keep the walls and furniture reading as one calm, open space.-->
<p>Colour has a big effect on how we perceive space. Light, neutral palettes are a reliable choice for small living rooms because they reflect light rather than absorbing it. White, cream, soft grey and pale taupe blur the line between walls and ceiling, so the edges of the room feel less defined. To keep a neutral room from feeling flat, mix textures: a chunky wool rug with a smooth linen sofa, velvet cushions and natural wood. Layered materials add depth without the strong contrasts that can visually break up a small room. Our <a href="/blog/living-room-colour-scheme-guide">living room colour scheme guide</a> covers more palettes.</p>

<h2 id="curated-minimalism">8. Curated minimalism</h2>
<p>Clutter is the enemy of a small living room, because every object on display is magnified. A curated approach means being selective about what stays. Instead of a shelf full of small trinkets, choose one or two statement objects that reflect your personality. Clear the coffee table and other surfaces regularly so the room keeps feeling fresh. Empty space is a design element in its own right: leaving some floor and wall bare lets the eye rest and shows off the pieces you have chosen to keep.</p>

<h2 id="flooring">9. Flooring</h2>
<p>The floor is the foundation of how a room reads. Choose a rug large enough to ground the whole seating area. A small rug floating in the middle of the floor chops the room into pieces and makes it feel disjointed. A rug that sits under at least the front legs of the sofa and chairs pulls the furniture together into one zone. Choose light colours or subtle, large-scale patterns; busy small patterns can feel chaotic in a tight space. With timber or laminate floors, running the boards along the longest dimension of the room helps it look longer.</p>

<h2 id="texture">10. Texture</h2>
<p>When the palette is kept simple, texture becomes the main source of interest. A jute rug, a leather armchair or silk curtains give a rich, high-end feel, adding visual weight without taking up physical space. A subtle grasscloth wallpaper, for example, brings a warmth and depth that paint alone cannot. These tactile details make a room feel considered and prove you do not need a large floor plan for a sophisticated result.</p>

<h2 id="drapery">11. Drapery</h2>
<!--figure:sunlit-sofa-sheer-curtains|Floor-length sheer curtains filter daylight and draw the eye up, making the ceiling feel higher.-->
<p>Curtains change how tall and wide a room feels. To make the ceiling seem higher, hang the rod well above the window frame, even right up at the ceiling, and let the fabric reach the floor or pool slightly. Choose sheers or light linens that let daylight through while giving privacy. To make a narrow window look wider, extend the rod beyond the frame so that open curtains cover the wall rather than the glass. More light comes in and the window looks more generous. See <a href="/blog/how-to-choose-curtains-for-your-living-room">how to choose curtains for your living room</a> for fabrics and fittings.</p>

<h2 id="greenery">12. Greenery</h2>
<!--figure:bright-living-room-rattan-sofa|Indoor plants and fresh flowers bring colour and life to a bright, compact living room.-->
<p>Plants are the finishing touch that brings a small living room to life, adding colour, texture and a vitality that artificial decor cannot match. Use them to soften sharp corners or fill awkward gaps. A tall, architectural plant such as a fiddle-leaf fig or snake plant adds height without taking much floor space. Trailing plants like pothos or string of pearls make use of vertical space and add lushness from above. Many people also simply find a room with plants more relaxing to be in.</p>
<blockquote><p><strong>Tip:</strong> Before buying anything, mark the footprint of new furniture on the floor with masking tape and live with it for a day. It is the quickest way to check walkways in a small room.</p></blockquote>

<h2 id="conclusion">Conclusion</h2>
<p>Decorating a small living room is an exercise in thoughtful curation and planning. Prioritise light, use the walls, and choose furniture that is both stylish and multifunctional, and you can create a room that feels spacious and sophisticated. The size of a room does not limit its potential.</p>
<p>For more ideas, read <a href="/blog/how-to-arrange-furniture-in-a-small-living-room">how to arrange furniture in a small living room</a> and our <a href="/blog/storage-ideas-for-small-homes">storage ideas for small homes</a>. If you would like a plan for your own space, our <a href="/services/small-space-apartment-decoration">small space and apartment decoration</a> service can help, or you can <a href="/book-consultation">book a consultation</a>.</p>
HTML;
    }
}
