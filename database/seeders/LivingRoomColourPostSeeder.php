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
 * The living room colour scheme guide, with photos from the media library placed inside the article.
 * Create-only: an existing slug (even soft-deleted) is skipped so admin edits are never overwritten.
 */
class LivingRoomColourPostSeeder extends Seeder
{
    use SeedsMedia;

    private const SLUG = 'living-room-colour-scheme-guide';

    public function run(): void
    {
        if (BlogPost::withTrashed()->where('slug', self::SLUG)->exists()) {
            return;
        }

        $service = Service::where('slug', 'living-room-decoration')->first();

        $post = BlogPost::create([
            'slug' => self::SLUG,
            'title' => 'The Ultimate Guide to Choosing a Living Room Colour Scheme',
            'excerpt' => 'Neutrals, jewel tones, coastal blues, earthy greens, greys, terracotta and pastels: how each palette changes the mood of a living room, and the rules that make any scheme work.',
            'body' => $this->withFigures(trim($this->body())),
            'featured_media_id' => $this->media('sunlit-living-room-sectional'),
            'author_id' => User::where('role', 'admin')->orderBy('id')->value('id'),
            'service_id' => $service?->id,
            'status' => 'published',
            'published_at' => now()->setTime(9, 0),
            'meta_title' => 'Living Room Colour Scheme Guide: 7 Palettes | Home Decor Studio',
            'meta_description' => 'How to choose a living room colour scheme: seven palettes from warm neutrals to jewel tones, plus the 60-30-10 rule, LRV, lighting and paint finishes.',
            'robots_index' => true,
        ]);

        $post->categories()->sync(BlogCategory::whereIn('slug', ['living-room-decor', 'wall-decor'])->pluck('id')->all());

        // Lead the living room service's "Further reading" with this guide.
        if ($service) {
            $ids = collect([$post->id])->merge($service->articles()->pluck('blog_posts.id'))->unique()->values();
            $service->articles()->sync($ids->mapWithKeys(fn ($id, $i) => [$id => ['sort_order' => $i + 1]])->all());
        }
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

    private function body(): string
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
}
