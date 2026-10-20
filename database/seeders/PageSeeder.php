<?php

namespace Database\Seeders;

use App\Models\Page;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

/**
 * Public pages and their ordered sections (see docs/ARCHITECTURE.md → Section types).
 *
 * In section definitions, `media` is a library slug (resolved to media_id), and any
 * `media` key inside data items is resolved to `media_id` the same way.
 * Hero images are unique per page. Re-running the seeder rebuilds each page's sections.
 */
class PageSeeder extends Seeder
{
    use SeedsMedia;

    private const TITLE_SUFFIX = ' | Home Decor Studio';

    private const CTA_BUTTONS = [
        ['label' => 'Book a Consultation', 'url' => '/book-consultation', 'style' => 'primary'],
        ['label' => 'Contact Us', 'url' => '/contact', 'style' => 'light'],
    ];

    private const WHY_CHOOSE_US = [
        'type' => 'features',
        'eyebrow' => 'Why choose us',
        'heading' => 'A considered approach to every home',
        'body' => 'We believe great decoration comes from listening carefully, choosing thoughtfully and finishing beautifully.',
        'data' => ['columns' => 4, 'items' => [
            ['icon' => 'heart', 'title' => 'Personalised design', 'text' => 'Every scheme starts with how you live, so the result feels unmistakably yours rather than a template.'],
            ['icon' => 'leaf', 'title' => 'Considered sourcing', 'text' => 'Furniture, fabrics and finishes are chosen for quality, comfort and longevity as well as appearance.'],
            ['icon' => 'file-text', 'title' => 'Transparent proposals', 'text' => 'You receive a clear proposal of scope and costs, and nothing proceeds without your approval.'],
            ['icon' => 'eye', 'title' => 'Attention to detail', 'text' => 'From hanging heights to the final cushion, we refine the details that make a room feel complete.'],
        ]],
    ];

    public function run(): void
    {
        foreach ($this->pages() as $slug => $page) {
            $this->upsertPage($slug, $page);
        }
    }

    private function pages(): array
    {
        return [
            'home' => [
                'title' => 'Home',
                'template' => 'home',
                'meta_title' => 'Bespoke Home Decoration Services',
                'meta_description' => 'Personalised home decoration for living rooms, bedrooms, kitchens and whole homes. Timeless, comfortable spaces designed around you.',
                'sections' => [
                    ['type' => 'announcement', 'body' => 'Now welcoming new home decoration projects — book your consultation today.', 'data' => ['link_label' => 'Book a consultation', 'link_url' => '/book-consultation']],
                    ['type' => 'hero', 'eyebrow' => 'Elevate your everyday living', 'heading' => 'Beautiful Home Decor, Thoughtfully Designed for You', 'body' => 'Transform your home with personalised decoration solutions that combine timeless elegance, comfort and functionality.', 'media' => 'double-height-ring-chandelier-fireplace', 'data' => [
                        'size' => 'full',
                        'buttons' => [
                            ['label' => 'Explore Our Services', 'url' => '/services', 'style' => 'primary'],
                            ['label' => 'View Our Portfolio', 'url' => '/gallery', 'style' => 'light'],
                        ],
                    ]],
                    ['type' => 'stats', 'heading' => 'At a glance', 'data' => ['items' => []]],
                    ['type' => 'text_image', 'eyebrow' => 'About the studio', 'heading' => 'Interiors that feel calm, personal and complete', 'body' => '<p>We are a home decor studio devoted to creating spaces that feel as good to live in as they look. We bring together colour, texture, furniture, lighting and styling into one considered scheme, shaped around your routines, your tastes and the character of your home.</p><p>Whether you are refreshing a single room or decorating an entire home, we guide you from the first conversation to the final styled detail.</p>', 'media' => 'warm-minimal-living-room-sunset', 'data' => ['image_position' => 'right', 'button' => ['label' => 'About Us', 'url' => '/about-us']]],
                    ['type' => 'services', 'eyebrow' => 'What we do', 'heading' => 'Decoration services for every room', 'body' => 'From individual rooms to complete homes, each service is tailored to your space, style and budget.', 'data' => ['limit' => 6, 'featured_only' => true]],
                    self::WHY_CHOOSE_US,
                    ['type' => 'projects', 'eyebrow' => 'Portfolio', 'heading' => 'Spaces we love', 'body' => 'A selection of interiors showing the palettes, materials and details that define our work.', 'data' => ['limit' => 6, 'featured_only' => true]],
                    ['type' => 'before_after', 'eyebrow' => 'Transformations', 'heading' => 'Before & after', 'body' => 'See how unfinished spaces become calm, complete and beautifully finished homes.', 'data' => ['items' => [
                        ['title' => 'Living room makeover', 'before' => 'living-room-before', 'after' => 'living-room-after', 'url' => '/services/living-room-decoration'],
                        ['title' => 'Bedroom makeover', 'before' => 'bedroom-before', 'after' => 'bedroom-after', 'url' => '/services/bedroom-decoration'],
                        ['title' => 'Bathroom renovation', 'before' => 'bathroom-before', 'after' => 'bathroom-after', 'url' => '/services/bathroom-decoration'],
                    ]]],
                    ['type' => 'process', 'eyebrow' => 'How it works', 'heading' => 'A clear, collaborative process', 'body' => 'Six considered steps take your home from first conversation to finished, styled rooms.', 'data' => ['layout' => 'grid']],
                    ['type' => 'styles', 'eyebrow' => 'Find your style', 'heading' => 'Decor styles we create', 'body' => 'Whatever your taste, we interpret it in a way that feels fresh, comfortable and personal.', 'data' => ['items' => [
                        ['title' => 'Modern', 'text' => 'Clean lines, confident shapes and a pared-back palette with carefully placed contrast.', 'media' => 'living-room-round-coffee-tables'],
                        ['title' => 'Minimalist', 'text' => 'Calm, uncluttered rooms where soft neutrals and natural textures do the talking.', 'media' => 'minimal-beige-living-room'],
                        ['title' => 'Luxury', 'text' => 'Rich materials, statement lighting and tailored upholstery for a sense of occasion.', 'media' => 'tufted-wall-crystal-chandelier-bedroom'],
                        ['title' => 'Classic', 'text' => 'Timeless proportions, elegant drapery and refined detailing with a lighter touch.', 'media' => 'classic-dining-room-drapes'],
                        ['title' => 'Contemporary', 'text' => 'Of-the-moment materials and forms — stone, walnut and sculptural pieces — balanced for everyday living.', 'media' => 'marble-tv-wall-living-room'],
                    ]]],
                    ['type' => 'text_image', 'eyebrow' => 'Tailored to you', 'heading' => 'Personalised solutions for every home', 'body' => '<p>No two homes — or households — are the same. We shape each project around your rooms, your preferences and your budget, recommending where to invest for the greatest impact and where simpler choices will serve you just as well.</p>', 'media' => 'dining-room-sculptural-mirrors', 'data' => [
                        'image_position' => 'left',
                        'bullets' => [
                            'Single rooms, multiple rooms or complete homes',
                            'Schemes built around your existing furniture and treasured pieces',
                            'Options presented to suit different budgets',
                            'Work delivered all at once or in convenient phases',
                        ],
                        'button' => ['label' => 'Book a Consultation', 'url' => '/book-consultation'],
                    ]],
                    ['type' => 'testimonials', 'eyebrow' => 'Kind words', 'heading' => 'What our clients say', 'data' => ['limit' => 3]],
                    ['type' => 'blog', 'eyebrow' => 'Journal', 'heading' => 'Decor ideas & inspiration', 'body' => 'Practical advice and ideas for creating a home you love.', 'data' => ['limit' => 3]],
                    ['type' => 'faqs', 'eyebrow' => 'FAQs', 'heading' => 'Frequently asked questions', 'data' => ['scope' => 'home']],
                    ['type' => 'cta', 'eyebrow' => 'Let\'s begin', 'heading' => 'Ready to transform your home?', 'body' => 'Tell us about your space and what you would like to change. We will help you shape the next step.', 'media' => 'living-room-pearl-chandelier-garden', 'data' => ['buttons' => self::CTA_BUTTONS]],
                ],
            ],

            'services' => [
                'title' => 'Our Services',
                'meta_title' => 'Home Decoration & Styling Services',
                'meta_description' => 'Explore our home decoration services: living rooms, bedrooms, kitchens, bathrooms, wall decor, curtains, lighting, styling and full homes.',
                'sections' => [
                    $this->pageHero('Our services', 'Home Decoration Services, Tailored to You', 'From a single statement wall to a complete home, we offer decoration services that bring beauty, comfort and function together.', 'living-room-pendant-cluster-slats'),
                    ['type' => 'text_image', 'eyebrow' => 'How we help', 'heading' => 'Every room, considered as part of the whole', 'body' => '<p>Our services can be booked individually or combined into a complete scheme. Some clients come to us for one specific need — new curtains, a feature wall, better lighting — while others want every room decorated under one cohesive vision.</p><p>Whichever you choose, the same care applies: we listen first, plan thoroughly and finish every detail with intention.</p>', 'media' => 'living-room-round-coffee-tables', 'data' => ['image_position' => 'right']],
                    ['type' => 'services', 'eyebrow' => 'Explore', 'heading' => 'All decoration services', 'data' => ['limit' => null, 'featured_only' => false]],
                    ['type' => 'features', 'eyebrow' => 'Expertise', 'heading' => 'Our decorating expertise', 'body' => 'The disciplines we bring together in every project.', 'data' => ['columns' => 3, 'items' => [
                        ['icon' => 'palette', 'title' => 'Colour & material palettes', 'text' => 'Cohesive schemes of paint, fabric, timber and stone, tested in your own light.'],
                        ['icon' => 'ruler', 'title' => 'Space planning', 'text' => 'Measured layouts that improve flow, comfort and the way rooms are used.'],
                        ['icon' => 'lamp', 'title' => 'Lighting design', 'text' => 'Layered ambient, task and accent lighting with statement fittings.'],
                        ['icon' => 'brush', 'title' => 'Wall treatments', 'text' => 'Wallpaper, panelling, decorative paint and feature walls with character.'],
                        ['icon' => 'blinds', 'title' => 'Soft furnishings', 'text' => 'Made-to-measure curtains, blinds, upholstery and layered textiles.'],
                        ['icon' => 'gem', 'title' => 'Styling & accessories', 'text' => 'Art, objects and greenery curated for a personal, finished look.'],
                    ]]],
                    ['type' => 'features', 'eyebrow' => 'Made for you', 'heading' => 'Customisation options', 'body' => 'Many elements of your scheme can be tailored to your space and taste.', 'data' => ['columns' => 4, 'items' => [
                        ['icon' => 'layers', 'title' => 'Fabrics & finishes', 'text' => 'Choose from a wide range of upholstery fabrics, timber finishes and metal tones.'],
                        ['icon' => 'ruler', 'title' => 'Made-to-measure', 'text' => 'Curtains, blinds, panelling and joinery sized precisely to your rooms.'],
                        ['icon' => 'sparkles', 'title' => 'Statement pieces', 'text' => 'Feature lighting, art and furniture selected to give each room its own identity.'],
                        ['icon' => 'calendar', 'title' => 'Flexible scope', 'text' => 'Decorate one room, several or the whole home — all at once or in phases.'],
                    ]]],
                    self::WHY_CHOOSE_US,
                    ['type' => 'projects', 'eyebrow' => 'Portfolio', 'heading' => 'Explore our work', 'data' => ['limit' => 3, 'featured_only' => true]],
                    ['type' => 'process', 'eyebrow' => 'Our process', 'heading' => 'From consultation to handover', 'data' => ['layout' => 'grid']],
                    ['type' => 'testimonials', 'eyebrow' => 'Kind words', 'heading' => 'What our clients say', 'data' => ['limit' => 3]],
                    ['type' => 'faqs', 'eyebrow' => 'FAQs', 'heading' => 'Questions about our services', 'data' => ['scope' => 'home']],
                    $this->cta('Not sure where to start?', 'Book a consultation and we will help you decide which services suit your home and goals.', 'marble-lounge-dining-open-plan'),
                ],
            ],

            'gallery' => [
                'title' => 'Gallery',
                'meta_title' => 'Interior Decor Portfolio & Gallery',
                'meta_description' => 'Browse our interior decor portfolio: living rooms, bedrooms, kitchens, bathrooms, studies and whole-home schemes in a range of styles.',
                'sections' => [
                    $this->pageHero('Portfolio', 'Our Interior Decor Gallery', 'Explore living rooms, bedrooms, kitchens, workspaces and complete homes, each shaped by a considered palette and careful detailing.', 'living-room-sculpture-statement-art'),
                    ['type' => 'text_image', 'eyebrow' => 'Inspiration', 'heading' => 'A closer look at the details', 'body' => '<p>Our gallery brings together interiors that show the materials, palettes and ideas we love working with — natural stone and walnut, layered neutrals, statement lighting and tailored textiles. Filter by room to find inspiration for your own home, and open any project to see more images and the thinking behind the design.</p>', 'media' => 'walnut-tv-wall-living-room', 'data' => ['image_position' => 'right']],
                    ['type' => 'portfolio', 'eyebrow' => 'Browse', 'heading' => 'Projects by room', 'data' => ['page_size' => 9]],
                    ['type' => 'before_after', 'eyebrow' => 'Transformations', 'heading' => 'Before & after'],
                    $this->cta('Inspired by what you see?', 'Let\'s talk about how these ideas could work in your home.', 'staircase-atrium-indoor-planting'),
                ],
            ],

            'about-us' => [
                'title' => 'About Us',
                'meta_title' => 'About Us: Personalised Home Decor',
                'meta_description' => 'Discover our approach to home decoration: personal, considered and detail-led design that creates calm, beautiful spaces to live in.',
                'sections' => [
                    $this->pageHero('About us', 'Designing Homes That Feel Like You', 'We create considered, comfortable interiors that reflect the people who live in them.', 'living-room-pearl-chandelier-garden'),
                    ['type' => 'text_image', 'eyebrow' => 'Our story', 'heading' => 'Why we do what we do', 'body' => '<p>We believe a home should do more than look beautiful — it should support the way you live, restore you at the end of the day and feel unmistakably your own. That belief shapes everything we offer, from a single styled room to a fully decorated home.</p><p>We work closely with each client, taking time to understand routines, tastes and the character of the property. The result is a scheme that brings colour, texture, furniture, lighting and personal objects together in balance.</p>', 'media' => 'coffee-table-styling-tray', 'data' => ['image_position' => 'right']],
                    ['type' => 'text_image', 'eyebrow' => 'The studio', 'heading' => 'A complete decoration service under one roof', 'body' => '<p>Our studio offers decoration and styling services for every room: living rooms, bedrooms, kitchens, bathrooms and workspaces, as well as wall treatments, curtains, lighting and accessories. For clients who want a cohesive result throughout, we provide full home decoration with one coordinated process.</p><p>From the first consultation to the final styling, you have a single team guiding the design, sourcing and installation.</p>', 'media' => 'site-plan-review-desk', 'data' => ['image_position' => 'left', 'button' => ['label' => 'Explore Our Services', 'url' => '/services']]],
                    ['type' => 'features', 'eyebrow' => 'Purpose', 'heading' => 'Our mission & vision', 'data' => ['columns' => 2, 'items' => [
                        ['icon' => 'compass', 'title' => 'Our mission', 'text' => 'To create personalised, beautifully balanced interiors that make everyday life more comfortable, calm and joyful — guided by careful listening and thoughtful design.'],
                        ['icon' => 'eye', 'title' => 'Our vision', 'text' => 'To be the studio people trust to turn a house into a home, known for considered design, honest communication and an eye for the details that make a room feel complete.'],
                    ]]],
                    ['type' => 'features', 'eyebrow' => 'Design philosophy', 'heading' => 'What guides our design', 'data' => ['columns' => 3, 'items' => [
                        ['icon' => 'home', 'title' => 'Live first, look second', 'text' => 'Rooms are planned around how you use them, then made beautiful.'],
                        ['icon' => 'layers', 'title' => 'Layered, not cluttered', 'text' => 'Texture, light and materials create depth without excess.'],
                        ['icon' => 'leaf', 'title' => 'Timeless over trend', 'text' => 'We favour natural materials and enduring forms that age gracefully.'],
                    ]]],
                    ['type' => 'team', 'eyebrow' => 'Our team', 'heading' => 'The people behind the studio', 'data' => ['items' => []]],
                    ['type' => 'features', 'eyebrow' => 'Expertise', 'heading' => 'Areas of expertise', 'data' => ['columns' => 4, 'items' => [
                        ['icon' => 'sofa', 'title' => 'Room decoration', 'text' => 'Living rooms, bedrooms, kitchens and bathrooms tailored to their purpose.'],
                        ['icon' => 'brush', 'title' => 'Walls & windows', 'text' => 'Wallpaper, panelling, paint finishes and made-to-measure curtains.'],
                        ['icon' => 'lamp', 'title' => 'Lighting & ceilings', 'text' => 'Layered lighting plans, statement fittings and ceiling details.'],
                        ['icon' => 'gem', 'title' => 'Furniture & styling', 'text' => 'Space planning, sourcing and the final styled layer.'],
                    ]]],
                    self::WHY_CHOOSE_US,
                    ['type' => 'projects', 'eyebrow' => 'Portfolio', 'heading' => 'A glimpse of our work', 'data' => ['limit' => 3, 'featured_only' => true]],
                    $this->cta('Let\'s create something beautiful together', 'Share your plans with us and we will help you take the first step.', 'living-room-sculptural-chandelier'),
                ],
            ],

            'our-process' => [
                'title' => 'Our Process',
                'meta_title' => 'Our Home Decoration Process',
                'meta_description' => 'How our home decoration process works, from initial consultation and space assessment to design concept, execution and final styling.',
                'sections' => [
                    $this->pageHero('Our process', 'A Clear Path From Idea to Finished Home', 'Six considered steps that keep you informed, involved and confident at every stage.', 'design-studio-drawings'),
                    ['type' => 'text_image', 'eyebrow' => 'Working together', 'heading' => 'Structured, transparent and collaborative', 'body' => '<p>Decorating a home involves many decisions. Our process breaks the journey into clear stages, so you always know what is happening, what comes next and where your input is needed.</p><p>You approve the design and the proposal before any ordering or installation begins, and we keep you updated throughout delivery.</p>', 'media' => 'cost-estimate-plans-review', 'data' => ['image_position' => 'right']],
                    ['type' => 'process', 'eyebrow' => 'Step by step', 'heading' => 'How we work', 'data' => ['layout' => 'timeline']],
                    ['type' => 'features', 'eyebrow' => 'What to expect', 'heading' => 'What to expect when working with us', 'data' => ['columns' => 4, 'items' => [
                        ['icon' => 'message-circle', 'title' => 'Clear communication', 'text' => 'Regular updates and a single point of contact throughout your project.'],
                        ['icon' => 'palette', 'title' => 'Visual concepts', 'text' => 'Palettes, materials and layouts presented so you can picture the result.'],
                        ['icon' => 'file-text', 'title' => 'Approval at each stage', 'text' => 'Nothing is ordered or installed until you are happy with the plan.'],
                        ['icon' => 'sparkles', 'title' => 'A finished, styled home', 'text' => 'Rooms handed over complete, styled and ready to enjoy.'],
                    ]]],
                    $this->cta('Start with a conversation', 'Book an initial consultation and we will talk through your home, your ideas and the next steps.', 'styled-cosy-living-room'),
                ],
            ],

            'testimonials' => [
                'title' => 'Testimonials',
                'meta_title' => 'Client Testimonials',
                'meta_description' => 'Read what clients say about working with our home decor studio on their living rooms, bedrooms, kitchens and complete homes.',
                'sections' => [
                    $this->pageHero('Testimonials', 'Kind Words From Our Clients', 'Feedback from homeowners we have had the pleasure of working with.', 'warm-living-room-pampas-art'),
                    ['type' => 'testimonials', 'eyebrow' => 'Reviews', 'heading' => 'What our clients say'],
                    $this->cta('Could your home be next?', 'Tell us about your space and let\'s explore what is possible.', 'living-room-abstract-art'),
                ],
            ],

            'faqs' => [
                'title' => 'FAQs',
                'meta_title' => 'Home Decoration FAQs',
                'meta_description' => 'Answers to common questions about our home decoration services, budgets, timelines, materials, customisation and booking a consultation.',
                'sections' => [
                    $this->pageHero('FAQs', 'Frequently Asked Questions', 'Everything you need to know about working with us, from first consultation to final styling.', 'sunlit-sofa-sheer-curtains'),
                    ['type' => 'faqs', 'eyebrow' => 'Help centre', 'heading' => 'Your questions, answered', 'data' => ['scope' => 'all']],
                    $this->cta('Still have a question?', 'Get in touch and we will be happy to help.', 'console-table-armchair-vignette'),
                ],
            ],

            'book-consultation' => [
                'title' => 'Book a Consultation',
                'meta_title' => 'Book a Home Decor Consultation',
                'meta_description' => 'Request a home decor consultation. Tell us about your space, style and goals, and we will be in touch to arrange a convenient time.',
                'sections' => [
                    $this->pageHero('Book a consultation', 'Let\'s Talk About Your Home', 'Share a few details about your space and plans, and we will be in touch to arrange your consultation.', 'open-plan-minimal-living-room'),
                    ['type' => 'consultation_form', 'eyebrow' => 'Request a consultation', 'heading' => 'Tell us about your project', 'body' => 'The more you share, the better we can prepare. Fields marked with an asterisk are required.'],
                    ['type' => 'process', 'eyebrow' => 'What happens next', 'heading' => 'Our process', 'data' => ['layout' => 'grid']],
                    ['type' => 'faqs', 'eyebrow' => 'FAQs', 'heading' => 'Before you book', 'data' => ['scope' => 'home']],
                ],
            ],

            'contact' => [
                'title' => 'Contact Us',
                'meta_title' => 'Contact Us',
                'meta_description' => 'Get in touch with our home decor studio. Send us a message about your project and we will respond as soon as we can.',
                'sections' => [
                    $this->pageHero('Contact', 'Get in Touch', 'Have a question or a project in mind? We would love to hear from you.', 'living-room-shelves-paper-lanterns'),
                    ['type' => 'contact', 'eyebrow' => 'Say hello', 'heading' => 'Send us a message', 'body' => 'Use the form below and we will get back to you as soon as possible.'],
                    ['type' => 'service_areas', 'eyebrow' => 'Where we work', 'heading' => 'Areas we serve'],
                    $this->cta('Prefer to plan in detail?', 'Book a consultation to discuss your home and project in depth.', 'white-marble-living-room'),
                ],
            ],

            'service-areas' => [
                'title' => 'Service Areas',
                'meta_title' => 'Areas We Serve',
                'meta_description' => 'Find out where our home decoration and styling services are available, and get in touch to check availability for your area.',
                'sections' => [
                    $this->pageHero('Service areas', 'Where We Work', 'Personalised home decoration services, delivered wherever you call home within our service area.', 'modern-villa-exterior-dusk'),
                    ['type' => 'text_image', 'eyebrow' => 'Availability', 'heading' => 'Bringing considered decor to your door', 'body' => '<p>We provide our full range of decoration and styling services across the areas listed below. If you do not see your location, please get in touch — we are always happy to discuss your project and confirm whether we can help.</p>', 'media' => 'family-house-exterior-lit', 'data' => ['image_position' => 'right', 'button' => ['label' => 'Contact Us', 'url' => '/contact']]],
                    ['type' => 'service_areas', 'eyebrow' => 'Locations', 'heading' => 'Areas we serve'],
                    $this->cta('Check availability for your home', 'Tell us where you are and what you have in mind.', 'finished-house-exterior-dusk'),
                ],
            ],

            'blog' => [
                'title' => 'Decor Ideas & Journal',
                'template' => 'blog',
                'meta_title' => 'Decor Ideas & Journal',
                'meta_description' => 'Home decor ideas, styling tips and inspiration for every room — from living rooms and bedrooms to lighting, wall decor and small spaces.',
                'sections' => [
                    $this->pageHero('Journal', 'Decor Ideas & Journal', 'Ideas, advice and inspiration for creating a home you love.', 'living-room-fireplace-artwork'),
                ],
            ],

            'privacy-policy' => $this->legalPage('Privacy Policy', 'How we collect, use and protect your personal information when you use our website and services.', self::PRIVACY),
            'terms-conditions' => $this->legalPage('Terms & Conditions', 'The terms that apply to your use of our website and to enquiries about our home decoration services.', self::TERMS),
            'cookie-policy' => $this->legalPage('Cookie Policy', 'Information about the cookies used on our website, why we use them and how you can manage your preferences.', self::COOKIES),
        ];
    }

    private function upsertPage(string $slug, array $page): void
    {
        $model = Page::withTrashed()->updateOrCreate(['slug' => $slug], [
            'title' => $page['title'],
            'template' => $page['template'] ?? 'default',
            'body' => $page['body'] ?? null,
            'meta_title' => $page['meta_title'].self::TITLE_SUFFIX,
            'meta_description' => $page['meta_description'],
            'og_media_id' => $this->heroMediaId($page['sections']),
            'robots_index' => true,
            'is_published' => true,
            'deleted_at' => null,
        ]);

        $model->sections()->delete();

        foreach ($page['sections'] as $i => $section) {
            $model->sections()->create([
                'type' => $section['type'],
                'eyebrow' => $section['eyebrow'] ?? null,
                'heading' => $section['heading'] ?? null,
                'body' => $section['body'] ?? null,
                'media_id' => isset($section['media']) ? $this->media($section['media']) : null,
                'data' => isset($section['data']) ? $this->resolveMedia($section['data']) : null,
                'is_enabled' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function heroMediaId(array $sections): ?int
    {
        foreach ($sections as $section) {
            if ($section['type'] === 'hero' && isset($section['media'])) {
                return $this->media($section['media']);
            }
        }

        return null;
    }

    /** Replace `media` / `before` / `after` slugs inside data items with `media_id` / `before_media_id` / `after_media_id`. */
    private function resolveMedia(array $data): array
    {
        if (isset($data['items'])) {
            $data['items'] = array_map(function (array $item) {
                foreach (['media' => 'media_id', 'before' => 'before_media_id', 'after' => 'after_media_id'] as $key => $column) {
                    if (isset($item[$key])) {
                        $item[$column] = $this->media($item[$key]);
                        unset($item[$key]);
                    }
                }

                return $item;
            }, $data['items']);
        }

        return $data;
    }

    private function pageHero(string $eyebrow, string $heading, string $body, string $media): array
    {
        return ['type' => 'hero', 'eyebrow' => $eyebrow, 'heading' => $heading, 'body' => $body, 'media' => $media, 'data' => ['size' => 'page', 'buttons' => []]];
    }

    private function cta(string $heading, string $body, string $media): array
    {
        return ['type' => 'cta', 'eyebrow' => 'Let\'s begin', 'heading' => $heading, 'body' => $body, 'media' => $media, 'data' => ['buttons' => self::CTA_BUTTONS]];
    }

    private function legalPage(string $title, string $description, string $body): array
    {
        return [
            'title' => $title,
            'template' => 'legal',
            'meta_title' => $title,
            'meta_description' => $description,
            'body' => $body,
            'sections' => [],
        ];
    }

    private const PRIVACY = <<<'HTML'
<p>This Privacy Policy explains how :site_name ("we", "us", "our") collects, uses, stores and protects your personal information when you visit our website, contact us or use our home decoration services. We are committed to handling your information responsibly and transparently.</p>

<h2>Information we collect</h2>
<p>We only collect the information we need to respond to you and provide our services. This may include:</p>
<ul>
<li><strong>Contact details</strong> — your name, email address, telephone number and preferred method of contact.</li>
<li><strong>Project information</strong> — details you share about your property, the rooms or services you are interested in, your budget range, preferred dates and any photographs or files you choose to upload.</li>
<li><strong>Communications</strong> — the content of messages, enquiries and correspondence you send to us.</li>
<li><strong>Technical information</strong> — your IP address, browser type, device information and the pages you visit, collected automatically when you use our website (see our Cookie Policy).</li>
</ul>

<h2>How we use your information</h2>
<p>We use your personal information to:</p>
<ul>
<li>respond to your enquiries and consultation requests;</li>
<li>prepare proposals and deliver the services you request;</li>
<li>communicate with you about your project, including appointments and updates;</li>
<li>maintain records of our work and meet our legal and accounting obligations;</li>
<li>improve our website, services and the experience we offer;</li>
<li>protect our website against misuse, spam and security threats.</li>
</ul>
<p>We will not use your information for marketing communications unless you have given your consent, and you may withdraw that consent at any time.</p>

<h2>Legal basis for processing</h2>
<p>Where applicable data protection law requires a legal basis, we process your information because it is necessary to take steps at your request before entering into a contract or to perform a contract with you; because it is in our legitimate interests to operate and improve our business, provided those interests are not overridden by your rights; because you have given consent; or because we must comply with a legal obligation.</p>

<h2>Sharing your information</h2>
<p>We do not sell your personal information. We may share it only where necessary with:</p>
<ul>
<li>trusted suppliers, installers and tradespeople involved in delivering your project;</li>
<li>service providers who support our business, such as website hosting, email and IT providers, who process data on our behalf under appropriate safeguards;</li>
<li>professional advisers, such as accountants or legal advisers;</li>
<li>authorities, where we are required to do so by law.</li>
</ul>

<h2>How long we keep your information</h2>
<p>We keep personal information only for as long as necessary for the purposes described in this policy, including to meet legal, accounting or reporting requirements. Enquiries that do not lead to a project are deleted after a reasonable period. When information is no longer needed, we securely delete or anonymise it.</p>

<h2>How we protect your information</h2>
<p>We use appropriate technical and organisational measures to protect your personal information against unauthorised access, loss or misuse, including secure connections, restricted access and regular review of our practices. No method of transmission over the internet is completely secure, but we work to protect your information to a high standard.</p>

<h2>Your rights</h2>
<p>Depending on where you live, you may have the right to:</p>
<ul>
<li>request access to the personal information we hold about you;</li>
<li>ask us to correct inaccurate or incomplete information;</li>
<li>ask us to delete your information;</li>
<li>object to or ask us to restrict certain processing;</li>
<li>request a copy of your information in a portable format;</li>
<li>withdraw consent at any time where processing is based on consent.</li>
</ul>
<p>To exercise any of these rights, please contact us at :email. You may also have the right to complain to your local data protection authority.</p>

<h2>Advertising</h2>
<p>We use Google AdSense to show advertisements on our website. Third-party vendors, including Google, use cookies to serve ads based on your previous visits to this website and other websites. Google's use of advertising cookies enables it and its partners to serve ads to you based on your visits to this and/or other sites on the internet.</p>
<p>You can opt out of personalised advertising by visiting <a href="https://adssettings.google.com" target="_blank" rel="noopener nofollow">Google Ads Settings</a>, or opt out of some third-party vendors' use of cookies for personalised advertising at <a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener nofollow">www.aboutads.info</a>. To learn more, read <a href="https://policies.google.com/technologies/partner-sites" target="_blank" rel="noopener nofollow">how Google uses information from sites that use its services</a>. Where the law requires it, we ask for your consent before personalised ads are shown.</p>

<h2>Third-party links</h2>
<p>Our website may contain links to other websites or social media platforms. We are not responsible for the privacy practices of those sites, and we encourage you to read their privacy policies.</p>

<h2>Changes to this policy</h2>
<p>We may update this Privacy Policy from time to time. The latest version will always be available on this page.</p>

<h2>Contact us</h2>
<p>If you have any questions about this Privacy Policy or how we handle your information, please contact :site_name at :email.</p>
HTML;

    private const TERMS = <<<'HTML'
<p>These Terms &amp; Conditions govern your use of the :site_name website and any enquiries you make about our home decoration services. By using this website, you agree to these terms. Please read them carefully.</p>

<h2>About these terms</h2>
<p>These terms apply to your use of our website. Any decoration project we undertake for you will be subject to a separate written proposal or agreement, which sets out the agreed scope, costs, timings and any project-specific terms. If there is any conflict between these terms and a signed project agreement, the project agreement will apply.</p>

<h2>Use of our website</h2>
<ul>
<li>You may use this website for lawful purposes only and in a way that does not infringe the rights of others.</li>
<li>You must not attempt to gain unauthorised access to the website, interfere with its operation or introduce malicious code.</li>
<li>You must not use the website to send unsolicited marketing or submit false or misleading information.</li>
</ul>

<h2>Enquiries and consultations</h2>
<p>Submitting an enquiry or consultation request through our website does not create a contract between us. We will review your request and respond to discuss your project. Any quotation we provide is based on the information available at the time and is tailored to your project following consultation.</p>

<h2>Information on this website</h2>
<p>We aim to keep the content on our website accurate and up to date, but it is provided for general information and inspiration only. Images show examples of interior styles and design approaches; actual materials, colours and finishes may vary. Nothing on this website constitutes a binding offer.</p>

<h2>Intellectual property</h2>
<p>All content on this website, including text, images, graphics, logos and design, is owned by or licensed to :site_name and is protected by intellectual property laws. You may view and share links to our pages for personal, non-commercial purposes, but you must not copy, reproduce, modify or distribute our content without our prior written permission.</p>

<h2>Uploaded content</h2>
<p>If you upload photographs, plans or other files when contacting us, you confirm that you have the right to share them and you grant us permission to use them solely for the purpose of responding to your enquiry and delivering our services.</p>

<h2>Third-party links</h2>
<p>Our website may include links to third-party websites for your convenience. We have no control over the content of those websites and accept no responsibility for them or for any loss or damage that may arise from your use of them.</p>

<h2>Limitation of liability</h2>
<p>To the fullest extent permitted by law, we exclude all liability for any loss or damage arising from your use of, or inability to use, this website or from reliance on its content. Nothing in these terms limits or excludes liability that cannot be limited or excluded by law.</p>

<h2>Privacy</h2>
<p>Our use of your personal information is described in our Privacy Policy and Cookie Policy.</p>

<h2>Changes to these terms</h2>
<p>We may revise these terms at any time by updating this page. Please check it from time to time to stay aware of any changes.</p>

<h2>Governing law</h2>
<p>These terms are governed by the laws of the jurisdiction in which :site_name operates, and any disputes will be subject to the courts of that jurisdiction.</p>

<h2>Contact us</h2>
<p>If you have any questions about these Terms &amp; Conditions, please contact us at :email.</p>
HTML;

    private const COOKIES = <<<'HTML'
<p>This Cookie Policy explains how :site_name uses cookies and similar technologies on our website, what they do and how you can control them.</p>

<h2>What are cookies?</h2>
<p>Cookies are small text files placed on your device when you visit a website. They are widely used to make websites work, to work more efficiently and to provide information to website owners. Cookies may be set by the website you are visiting ("first-party cookies") or by other services used on the page ("third-party cookies").</p>

<h2>How we use cookies</h2>
<p>We use cookies for the following purposes:</p>
<ul>
<li><strong>Strictly necessary cookies</strong> — essential for the website to function, for example to keep the site secure, protect forms against misuse and remember your session. These cannot be switched off.</li>
<li><strong>Preference cookies</strong> — remember choices you make, such as your cookie preferences, to improve your experience.</li>
<li><strong>Analytics cookies</strong> — help us understand how visitors use our website, such as which pages are visited most often, so we can improve it. These are used only with your consent where required by law.</li>
<li><strong>Advertising cookies</strong> — set by Google and its partners to show and measure the ads on our website, including personalised ads based on your visits to this and other websites where you have agreed to them. See the Advertising section of our <a href="/privacy-policy">Privacy Policy</a> for how to opt out.</li>
<li><strong>Embedded content</strong> — some pages may include content from third-party services, such as maps or videos, which may set their own cookies.</li>
</ul>

<h2>Managing your cookie preferences</h2>
<p>Where required, we will ask for your consent before setting non-essential cookies. You can change your preferences at any time. Most web browsers also allow you to control cookies through their settings, including blocking or deleting them. Please note that blocking some types of cookies may affect how the website works.</p>
<p>To find out more about cookies and how to manage them, visit your browser's help pages.</p>

<h2>Third-party cookies</h2>
<p>Third-party providers whose services we use may set cookies in accordance with their own privacy and cookie policies. We do not control these cookies and recommend that you review the relevant providers' policies.</p>

<h2>Changes to this policy</h2>
<p>We may update this Cookie Policy from time to time, for example if we introduce new services or technologies. The latest version will always be available on this page.</p>

<h2>Contact us</h2>
<p>If you have any questions about our use of cookies, please contact :site_name at :email.</p>
HTML;
}
