<?php

namespace Database\Seeders;

use App\Models\Service;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

/**
 * The 10 core services. Copy describes each service, its approach and options only —
 * no claims about past results, clients or figures.
 *
 * Pairs inside arrays are [title, text]; sections are [heading, html body, media slug].
 */
class ServiceSeeder extends Seeder
{
    use SeedsMedia;

    protected const TITLE_SUFFIX = ' | Home Decor Studio';

    protected const FEATURED_COUNT = 6;

    private const SERVICES = [
        'living-room-decoration' => [
            'name' => 'Living Room Decoration',
            'icon' => 'sofa',
            'short_description' => 'Welcoming living rooms shaped around how you relax and entertain, with considered layouts, layered textures and lighting that carries the room from morning to evening.',
            'hero_title' => 'Professional Living Room Decoration Services',
            'hero_description' => 'From a gentle refresh to a complete transformation, we design living rooms that feel effortless to spend time in and beautiful to come home to.',
            'hero' => 'sunlit-living-room-sectional',
            'intro' => '<p>The living room asks more of its design than almost any other space. It is where you unwind at the end of the day, gather with family, welcome guests and, increasingly, work or read in quiet corners. Our living room decoration service begins by understanding all of those roles, so the finished room supports the way you actually live rather than an idealised version of it.</p><p>We look at seating arrangements and circulation first, making sure conversation flows naturally and pathways stay clear. From there we build the scheme in layers: a considered palette, wall finishes, flooring and rugs, upholstery, window dressings and a lighting plan that offers both brightness and warmth.</p><p>The final layer is personality. Artwork, books, ceramics and textiles bring depth and character, and we take care to integrate the pieces you already love alongside anything new, so the room feels collected and personal rather than bought all at once.</p>',
            'included' => [
                ['Layout & seating plan', 'A furniture plan that balances conversation, television viewing and easy movement through the room.'],
                ['Colour & material palette', 'A cohesive scheme of paint, wall finishes, fabrics and flooring tones, tested against your natural light.'],
                ['Furniture selection', 'Sofas, armchairs, coffee and side tables chosen for comfort, scale and durability as well as looks.'],
                ['Lighting plan', 'Layered ambient, task and accent lighting so the room adapts from bright afternoons to relaxed evenings.'],
                ['Soft furnishings', 'Rugs, cushions, throws and curtains that add warmth, texture and acoustic comfort.'],
                ['Final styling', 'Artwork, shelving and accessories arranged to give the finished room a personal, lived-in character.'],
            ],
            'styles' => [
                ['Warm Minimalist', 'Calm, pared-back rooms in soft neutrals, with natural timber and tactile fabrics bringing warmth.'],
                ['Contemporary Luxe', 'Statement lighting, marble or stone accents and rich upholstery for an elegant, polished feel.'],
                ['Modern Organic', 'Curved silhouettes, natural fibres, plants and earthy tones for a relaxed, grounded atmosphere.'],
                ['Classic Elegance', 'Symmetry, refined detailing and timeless furniture forms balanced with a lighter, modern palette.'],
                ['Mid-Century Inspired', 'Walnut tones, tapered legs and graphic accents, softened with layered textiles.'],
            ],
            'benefits' => [
                ['A room that works every day', 'Layouts are planned around your routines, so comfort and practicality are built in from the start.'],
                ['Cohesive, considered finish', 'Every element — from rug to lampshade — is chosen as part of one connected scheme.'],
                ['Your pieces, reimagined', 'We find ways to give existing furniture and treasured objects a natural place in the new design.'],
                ['Less guesswork, fewer mistakes', 'Scale, colour and proportion are resolved before anything is ordered.'],
            ],
            'materials' => [
                ['Performance upholstery', 'Hard-wearing fabrics with a soft hand, suited to family life and frequent use.'],
                ['Natural timbers', 'Oak and walnut finishes for joinery, tables and flooring that add warmth and longevity.'],
                ['Stone & marble accents', 'Coffee tables, fireplace surrounds and feature surfaces with natural veining.'],
                ['Wool & natural-fibre rugs', 'Textured rugs that anchor seating areas and soften acoustics.'],
                ['Linen & sheer textiles', 'Curtains and cushions that filter light and layer the scheme gently.'],
            ],
            'meta_title' => 'Living Room Decoration Services',
            'meta_description' => 'Personalised living room decoration: layouts, colour palettes, furniture, lighting and styling for a room that feels welcoming every day.',
            'sections' => [
                ['Designing around how you gather', '<p>Before choosing a single cushion, we think about how the room will be used. Do you host often, or is this mainly a family retreat? Is the television central, or would you prefer the focus on a fireplace, a view or a wall of books? The answers shape where seating sits, how many people it should accommodate comfortably and which zones need their own light.</p><p>In open-plan homes, we also consider how the living area relates to the kitchen and dining spaces, using rugs, lighting and furniture placement to define each zone while keeping a sense of connection and flow.</p>', 'walnut-stone-living-room'],
                ['Layering texture and warmth', '<p>A truly comfortable living room rarely relies on colour alone. We combine contrasting textures — nubby bouclé against smooth leather, matte plaster beside polished stone, chunky wool under fine linen — so the room feels rich and inviting even in a restrained palette.</p><p>Warm lighting, natural materials and a few well-chosen statement pieces complete the scheme, giving the space depth without clutter.</p>', 'minimal-living-room-fireplace'],
            ],
            'gallery' => ['living-room-pearl-chandelier-garden', 'open-plan-minimal-living-room', 'warm-minimal-living-room-sunset', 'double-height-living-room-fireplace', 'marble-fireplace-black-sofa'],
            'related' => ['furniture-styling', 'lighting-ceiling-decor', 'curtains-window-treatments'],
        ],

        'bedroom-decoration' => [
            'name' => 'Bedroom Decoration',
            'icon' => 'bed',
            'short_description' => 'Restful, beautifully layered bedrooms designed for better sleep and calmer mornings, combining soft palettes, tailored headboards, gentle lighting and thoughtful storage.',
            'hero_title' => 'Bespoke Bedroom Decoration Services',
            'hero_description' => 'We create bedrooms that feel like a retreat — serene, comfortable and personal — whether it is a principal suite, a guest room or a child\'s first big-bed room.',
            'hero' => 'upholstered-bed-gold-accents',
            'intro' => '<p>A bedroom should be the quietest, most restorative room in the home. Our bedroom decoration service focuses on creating that sense of calm through a balanced palette, soft textures and lighting that can be dimmed to a gentle glow at the end of the day.</p><p>We consider the bed as the anchor of the room, often designing around a tailored or upholstered headboard, a panelled feature wall or a carefully chosen statement light. Bedside arrangements, reading lamps, dressing areas and wardrobes are then planned so that everything you need has a natural, uncluttered place.</p><p>Every bedroom is different. A principal suite may call for a seating corner or a dressing table, while a guest room needs to feel welcoming yet adaptable. We tailor each scheme to the people who will use it, choosing materials that feel good to the touch and finishes that will stay beautiful for years.</p>',
            'included' => [
                ['Bed wall & headboard design', 'Upholstered, panelled or timber headboard walls that give the room a strong, restful focal point.'],
                ['Calming colour scheme', 'Soft, light-sensitive palettes chosen to feel serene in both daylight and lamplight.'],
                ['Bedside & reading lighting', 'Pendants, wall lights and dimmable lamps positioned for reading and winding down.'],
                ['Storage planning', 'Wardrobes, chests and bedside storage arranged to keep surfaces clear and the room tranquil.'],
                ['Bedding & textiles', 'Layered bedding, throws, rugs and curtains selected for comfort, texture and light control.'],
            ],
            'styles' => [
                ['Serene Neutral', 'Ivory, oatmeal and stone tones with soft textures for a calm, hotel-like retreat.'],
                ['Classic Luxury', 'Tufted headboards, crystal or glass chandeliers and symmetrical bedside arrangements.'],
                ['Warm Timber', 'Walnut or oak panelling, leather accents and cosy layered textiles.'],
                ['Soft Contemporary', 'Curved upholstered beds, sculptural lighting and gentle plaster finishes.'],
            ],
            'benefits' => [
                ['Better rest', 'Lighting, colour and blackout options are planned to support relaxation and sleep.'],
                ['Clutter-free calm', 'Thoughtful storage keeps everyday items out of sight and surfaces serene.'],
                ['A personal sanctuary', 'The scheme reflects your tastes, from fabrics underfoot to the art you wake up to.'],
                ['Tailored to every bedroom', 'Principal, guest and children\'s rooms each receive a design suited to their purpose.'],
            ],
            'materials' => [
                ['Upholstered headboards', 'Velvet, bouclé or linen headboards in channel, tufted or panelled designs.'],
                ['Timber wall panelling', 'Warm walnut or painted panels that add architecture and acoustic softness.'],
                ['Natural bedding fabrics', 'Cotton, linen and wool layers that breathe and age gracefully.'],
                ['Blackout & sheer curtains', 'Layered window dressings that balance privacy, daylight and darkness.'],
                ['Marble & brass accents', 'Bedside tables, lamps and hardware that bring quiet refinement.'],
            ],
            'meta_title' => 'Bedroom Decoration Services',
            'meta_description' => 'Bespoke bedroom decoration for calm, restful spaces: headboard walls, soft palettes, gentle lighting, storage and layered textiles.',
            'sections' => [
                ['The headboard wall as a focal point', '<p>The wall behind the bed is the first thing you see when you walk into a bedroom, and the backdrop to every quiet morning. We often treat it as a feature in its own right, using full-height upholstered panels, timber slats, fluted detailing or integrated cove lighting to frame the bed.</p><p>These details add a sense of architecture and softness at the same time, improving acoustics and creating a cocooning feel. Bedside tables, wall lights and artwork are then aligned to the headboard so the whole composition feels balanced and intentional.</p>', 'curved-upholstered-bed'],
                ['Light for every hour', '<p>Bedrooms need lighting that changes through the day. We combine soft ambient light, directional reading lights and low-level accent lighting so you can move from getting ready in the morning to winding down at night without harsh overhead glare. Dimmers, warm colour temperatures and layered window treatments all play a part.</p>', 'bedroom-walnut-panels'],
            ],
            'gallery' => ['bedroom-lit-headboard', 'symmetrical-panelled-bedroom', 'bedroom-panelled-headboard-wall', 'bedroom-pendant-lights-cove-ceiling', 'bedroom-fluted-wall-light-sculpture', 'classic-luxury-bedroom-chandelier'],
            'related' => ['curtains-window-treatments', 'lighting-ceiling-decor', 'wall-decor-wallpaper'],
        ],

        'kitchen-decoration' => [
            'name' => 'Kitchen Decoration',
            'icon' => 'utensils',
            'short_description' => 'Kitchens that are as inviting as they are practical, refreshed through finishes, lighting, bar seating, open shelving and styling that makes the heart of the home shine.',
            'hero_title' => 'Stylish Kitchen Decoration Services',
            'hero_description' => 'We help kitchens feel warmer, brighter and more sociable — refining finishes, lighting and styling so cooking, dining and gathering all feel effortless.',
            'hero' => 'white-kitchen-island-bar-stools',
            'intro' => '<p>The kitchen has become the true centre of the home: a place to cook, eat, work, talk and entertain. Our kitchen decoration service concentrates on the elements that shape how the room looks and feels, without the disruption of a full rebuild unless you want one.</p><p>We look at the surfaces you see most — splashbacks, worktop styling, cabinetry colour and hardware — and at the lighting that defines the space, from pendants over an island to under-cabinet strips that make preparation easier. Seating is considered carefully too, whether that is a row of bar stools, a banquette or a dining table that links the kitchen to the rest of the home.</p><p>Finally, we style open shelving and display areas so that everyday objects become part of the design. The result is a kitchen that remains highly practical while feeling warm, personal and inviting.</p>',
            'included' => [
                ['Finish & colour refresh', 'Cabinet colours, hardware and splashback options that update the kitchen\'s character.'],
                ['Island & pendant lighting', 'Statement pendants and layered task lighting sized and positioned for the island and worktops.'],
                ['Bar & dining seating', 'Stools, chairs and banquettes chosen for comfort, height and easy cleaning.'],
                ['Open shelving styling', 'Curated arrangements of ceramics, glassware, cookbooks and plants.'],
                ['Kitchen–dining connection', 'Rugs, lighting and furniture that tie the kitchen into adjoining living and dining areas.'],
            ],
            'styles' => [
                ['Clean Handleless', 'Seamless white or grey cabinetry, crisp stone surfaces and minimal visual noise.'],
                ['Warm Walnut', 'Rich timber islands, globe pendants and brass or black accents.'],
                ['Scandinavian Natural', 'Light timber, open shelves, greenery and simple matte finishes.'],
                ['Marble Statement', 'Book-matched or veined stone splashbacks and islands as the room\'s centrepiece.'],
            ],
            'benefits' => [
                ['A more sociable kitchen', 'Seating and lighting are planned so family and guests can gather comfortably.'],
                ['Practical beauty', 'Finishes are selected for durability and easy upkeep as well as appearance.'],
                ['A connected home', 'The kitchen\'s palette flows naturally into neighbouring rooms.'],
                ['Transformation without upheaval', 'Decorative updates can refresh the space dramatically with limited disruption.'],
            ],
            'materials' => [
                ['Natural & engineered stone', 'Marble, quartz and similar surfaces for splashbacks and islands.'],
                ['Timber veneers', 'Walnut and oak finishes that bring warmth to cabinetry and shelving.'],
                ['Brass, bronze & matte black hardware', 'Handles, taps and fittings that define the kitchen\'s tone.'],
                ['Wipe-clean upholstery', 'Practical fabrics and leathers for stools and banquettes.'],
            ],
            'meta_title' => 'Kitchen Decoration Services',
            'meta_description' => 'Kitchen decoration that blends beauty and practicality: finishes, island pendants, bar seating, open-shelf styling and a warm, sociable feel.',
            'sections' => [
                ['Lighting the heart of the home', '<p>Good kitchen lighting does several jobs at once. Pendants over an island create a focal point and define the space, while discreet task lighting under cabinets and shelves makes preparation safer and more pleasant. We then add softer ambient light so the kitchen can feel relaxed in the evening, especially in open-plan homes where it is always on view.</p><p>We help you choose pendant sizes, spacing and hanging heights that suit your ceiling and island length, so the fittings feel generous without overwhelming the room.</p>', 'walnut-kitchen-island-pendants'],
                ['Styling shelves with purpose', '<p>Open shelving can look effortless or cluttered — the difference lies in editing. We combine everyday essentials with a few decorative pieces, grouping by colour, height and material, and leave breathing space so the arrangement feels calm and easy to maintain.</p>', 'kitchen-open-shelving-plants'],
            ],
            'gallery' => ['open-kitchen-marble-island', 'white-handleless-kitchen', 'dining-room-ring-pendants', 'dining-room-industrial-pendants'],
            'related' => ['lighting-ceiling-decor', 'home-accessories-styling', 'full-home-decoration'],
        ],

        'bathroom-decoration' => [
            'name' => 'Bathroom Decoration',
            'icon' => 'bath',
            'short_description' => 'Spa-like bathrooms with warm timber vanities, sculpted mirrors, soft lighting and natural finishes that turn daily routines into calm, restorative moments.',
            'hero_title' => 'Elegant Bathroom Decoration Services',
            'hero_description' => 'We bring warmth, calm and a sense of quiet luxury to bathrooms and cloakrooms through carefully chosen finishes, lighting, mirrors and accessories.',
            'hero' => 'bathroom-freestanding-tub',
            'intro' => '<p>Bathrooms are often the smallest rooms in the home, yet they set the tone for the start and end of every day. Our bathroom decoration service focuses on creating a feeling of calm and quiet indulgence, even in compact spaces.</p><p>We pay close attention to the elements that make the biggest visual difference: the vanity and basin, mirrors and their lighting, wall and floor finishes, and the textiles and accessories that soften hard surfaces. Natural timber, stone-effect finishes, brushed brass and matte black details can each change the character of a bathroom entirely, and we help you choose a combination that suits your home.</p><p>Lighting deserves particular care. Backlit mirrors, niche lighting and gentle wall lights offer flattering, practical light for grooming while allowing a softer, spa-like mood for a relaxing bath.</p>',
            'included' => [
                ['Vanity & basin styling', 'Floating, timber or stone vanities paired with vessel or integrated basins.'],
                ['Mirror selection', 'Round, arched or backlit mirrors sized and positioned to suit the vanity and wall.'],
                ['Bathroom lighting', 'Mirror, niche and ambient lighting for both practical grooming and relaxed bathing.'],
                ['Finish & tile direction', 'Wall and floor finishes chosen for texture, tone and easy upkeep.'],
                ['Textiles & accessories', 'Towels, bath mats, plants and vessels that soften and personalise the space.'],
            ],
            'styles' => [
                ['Warm Spa', 'Oak or walnut vanities, natural stone tones and soft, warm lighting.'],
                ['Minimal Hotel', 'Floating vanities, large-format surfaces and backlit mirrors for a clean, refined look.'],
                ['Organic Natural', 'Plants, textured finishes and handmade ceramics for an earthy, relaxed feel.'],
                ['Classic Refined', 'Freestanding baths, brass fittings and symmetrical double vanities.'],
            ],
            'benefits' => [
                ['A daily retreat', 'Lighting and materials are chosen to make the bathroom feel calm and restorative.'],
                ['Flattering, practical light', 'Mirror and ambient lighting are balanced for grooming and relaxation.'],
                ['Small spaces, maximised', 'Mirrors, wall-hung furniture and light finishes help compact rooms feel larger.'],
                ['Easy to live with', 'Moisture-suitable, low-maintenance finishes keep the room looking fresh.'],
            ],
            'materials' => [
                ['Moisture-suitable timber', 'Sealed oak and walnut finishes for vanities and shelving.'],
                ['Stone & stone-effect surfaces', 'Marble, terrazzo and porcelain alternatives for walls, floors and tops.'],
                ['Brushed brass & matte black', 'Taps, wall lights and accessories that set the room\'s tone.'],
                ['Backlit & framed mirrors', 'Mirrors that double as a soft light source and a decorative feature.'],
            ],
            'meta_title' => 'Bathroom Decoration Services',
            'meta_description' => 'Bathroom decoration with warm vanities, sculpted mirrors, soft lighting and natural finishes for a calm, spa-like retreat at home.',
            'sections' => [
                ['Creating a spa-like atmosphere', '<p>A sense of calm in a bathroom comes from restraint and warmth working together. We favour a limited palette of natural tones, a few tactile materials and lighting that can be softened when you want to relax. Timber vanities bring warmth against stone or tiled surfaces, while plants and handmade ceramics add life and texture.</p><p>Thoughtful storage is just as important: when everyday products have a discreet home, the room instantly feels more serene. We plan niches, shelves and vanity drawers so the finished bathroom stays as calm as the day it was styled.</p>', 'oak-vanity-round-mirror'],
            ],
            'gallery' => ['double-vanity-bathroom-wood', 'backlit-round-mirror-bathroom', 'oak-vanity-round-mirror'],
            'related' => ['lighting-ceiling-decor', 'home-accessories-styling', 'bedroom-decoration'],
        ],

        'wall-decor-wallpaper' => [
            'name' => 'Wall Decor & Wallpaper',
            'icon' => 'brush',
            'short_description' => 'Feature walls with real character — wallpaper, timber and fluted panelling, stone, mirrors and art — designed to add depth, texture and personality to any room.',
            'hero_title' => 'Creative Wall Decor & Wallpaper Services',
            'hero_description' => 'Walls are the largest canvas in your home. We transform them with wallpaper, panelling, decorative finishes and art to give every room a distinctive character.',
            'hero' => 'timber-panelled-living-room-art',
            'intro' => '<p>Few decorative changes are as transformative as rethinking the walls. A well-chosen wallpaper, a run of fluted timber panels or a book-matched stone feature can give a room instant architecture and identity. Our wall decor service helps you choose the right treatment for each space and integrate it naturally into the wider scheme.</p><p>We consider how walls are seen — from the doorway, from the sofa, from the bed — and select treatments that create focus where it matters. Media walls, headboard walls, dining room features and entrance halls are all opportunities for something special, while quieter textures and paint finishes keep supporting walls calm.</p><p>Art and mirrors complete the picture. We advise on scale, framing and hanging heights, curate gallery walls and position mirrors to reflect light and views, so every wall in the room contributes to a balanced whole.</p>',
            'included' => [
                ['Feature wall design', 'Concepts for media walls, headboard walls and dining features tailored to each room.'],
                ['Wallpaper selection', 'Patterned, textured and mural wallpapers chosen for scale, tone and durability.'],
                ['Panelling & 3D finishes', 'Fluted, slatted, geometric and backlit panel options with integrated detailing.'],
                ['Paint & decorative finishes', 'Colour consultancy, limewash, plaster-effect and tonal paint schemes.'],
                ['Art & mirror curation', 'Sourcing, framing advice and hanging plans for artwork and mirrors.'],
                ['Installation coordination', 'Scheduling and overseeing specialist installers for a precise finish.'],
            ],
            'styles' => [
                ['Timber Warmth', 'Walnut and oak slats or panels that bring natural warmth and acoustic softness.'],
                ['Stone Statement', 'Book-matched marble or stone-effect features for drama and luxury.'],
                ['Geometric & Inlay', 'Metallic inlays and geometric panelling for a bold, tailored look.'],
                ['Soft Textural', 'Fluted plaster, tufted upholstery and textured wallpapers for quiet depth.'],
                ['Gallery & Mirror', 'Curated art groupings and sculptural mirrors that personalise a room.'],
            ],
            'benefits' => [
                ['Instant character', 'A single feature wall can redefine a room without changing its layout.'],
                ['Depth and texture', 'Panelling, plaster and wallpaper add dimension that paint alone cannot.'],
                ['A focal point with purpose', 'Feature walls draw the eye to where you want it — the bed, the fireplace, the view.'],
                ['Cohesion across rooms', 'Wall treatments are coordinated so the home feels connected from room to room.'],
            ],
            'materials' => [
                ['Designer wallpapers', 'Grasscloth, textured vinyl, silk-effect and pattern papers.'],
                ['Timber slats & panels', 'Veneered and solid timber panelling with optional integrated lighting.'],
                ['Natural & engineered stone', 'Marble and sintered-stone slabs for feature and media walls.'],
                ['Decorative plasters & limewash', 'Matte, mineral finishes with gentle movement and depth.'],
                ['Upholstered wall panels', 'Tufted or channelled panels for bedrooms and snug spaces.'],
            ],
            'meta_title' => 'Wall Decor & Wallpaper Services',
            'meta_description' => 'Wall decor and wallpaper services: feature walls, timber and fluted panelling, stone, decorative paint finishes, art and mirror curation.',
            'sections' => [
                ['Panelling, light and dimension', '<p>Three-dimensional wall treatments bring architecture to otherwise plain rooms. Fluted or wave-profile panels catch the light differently throughout the day, while integrated LED lighting behind or within the panels can turn a wall into a soft, glowing backdrop in the evening.</p><p>We help you select profiles, finishes and lighting temperatures that suit the room\'s proportions, and coordinate with installers so joints, edges and switches are resolved neatly.</p>', 'backlit-3d-wave-wall'],
                ['Upholstered and tufted walls', '<p>In bedrooms, dressing rooms and snugs, upholstered wall panels add luxurious softness and help absorb sound. Diamond-tufted, channelled or simple padded panels in velvet, linen or bouclé create a cocooning backdrop that pairs beautifully with statement lighting and polished accents.</p>', 'tufted-wall-crystal-chandelier-bedroom'],
            ],
            'gallery' => ['marble-tv-wall-living-room', 'walnut-tv-wall-living-room', 'gold-geometric-wall-panels', 'dining-room-sculptural-mirrors', 'bedroom-fluted-wall-light-sculpture'],
            'related' => ['living-room-decoration', 'lighting-ceiling-decor', 'home-accessories-styling'],
        ],

        'curtains-window-treatments' => [
            'name' => 'Curtains & Window Treatments',
            'icon' => 'blinds',
            'short_description' => 'Made-to-measure curtains, sheers and blinds that frame your windows beautifully, soften each room and give you complete control over light and privacy.',
            'hero_title' => 'Made-to-Measure Curtains & Window Treatments',
            'hero_description' => 'Windows shape the light, mood and proportions of every room. We design curtains, sheers and blinds that dress them beautifully and work perfectly day to day.',
            'hero' => 'fluted-walls-sheer-curtains-lounge',
            'intro' => '<p>Window treatments are where function and beauty meet most directly. The right curtains can make ceilings feel higher, windows feel grander and rooms feel warmer and quieter, while giving you control over privacy and daylight. Our curtains and window treatments service covers everything from the initial concept to measuring, making and fitting.</p><p>We start by understanding each room\'s needs. A bedroom may need blackout lining and a soft sheer layer; a living room with a view may call for floor-to-ceiling sheers that filter light without hiding the outlook; a formal dining room might suit classic drapes with a generous fall. Track and pole choices, heading styles and fabric weights are selected to suit the architecture and the way you live.</p><p>Fabrics are chosen with care, balancing colour, texture and drape with practical considerations such as sunlight exposure and ease of cleaning.</p>',
            'included' => [
                ['Window assessment & measuring', 'Precise measurements and advice on proportions, stack-back and hanging heights.'],
                ['Fabric & lining selection', 'Sheers, linens, velvets and blackout or thermal linings tailored to each room.'],
                ['Curtain & blind design', 'Heading styles, lengths, layering and blind types for every window shape.'],
                ['Tracks, poles & motorisation', 'Discreet ceiling tracks, decorative poles and optional motorised systems.'],
                ['Professional fitting', 'Installation and dressing so curtains hang and fold beautifully from day one.'],
            ],
            'styles' => [
                ['Floor-to-Ceiling Sheers', 'Light, airy layers that soften glazing and blur the line between inside and out.'],
                ['Layered Sheer & Blackout', 'A practical pairing for bedrooms, offering softness by day and darkness by night.'],
                ['Classic Drapes', 'Full, generously lined curtains with refined headings for formal rooms.'],
                ['Tailored Blinds', 'Roman, roller and pleated blinds for kitchens, bathrooms and smaller windows.'],
            ],
            'benefits' => [
                ['Control over light & privacy', 'Layered treatments let you adjust each room through the day.'],
                ['Better proportions', 'Correctly hung curtains make windows look larger and ceilings taller.'],
                ['Warmth and quiet', 'Lined and interlined curtains improve comfort and soften acoustics.'],
                ['A perfect fit', 'Made-to-measure pieces suit unusual and oversized windows that ready-made options cannot.'],
            ],
            'materials' => [
                ['Linen & linen blends', 'Relaxed, natural texture with an elegant, informal drape.'],
                ['Voile & sheer weaves', 'Fine fabrics that diffuse daylight softly while preserving privacy.'],
                ['Velvet & heavyweight weaves', 'Rich, insulating fabrics for formal rooms and cosy bedrooms.'],
                ['Blackout & thermal linings', 'Linings that block light and help retain warmth.'],
                ['Ceiling tracks & decorative poles', 'Concealed or statement hardware to suit each interior.'],
            ],
            'meta_title' => 'Curtains & Window Treatments',
            'meta_description' => 'Made-to-measure curtains, sheers and blinds: expert measuring, fabric selection, tracks, poles, motorisation and professional fitting.',
            'sections' => [
                ['Classic drapes for formal rooms', '<p>In dining rooms and formal living spaces, generous lined drapes add grandeur and frame tall windows elegantly. We advise on heading styles such as pinch pleat or wave, on fabric weight and on how far the curtains should extend beyond the window, so they stack neatly when open and fall in even, graceful folds when drawn.</p><p>Tiebacks, trims and pelmets can be added for a more traditional look, or omitted for a cleaner contemporary interpretation.</p>', 'classic-dining-room-drapes'],
                ['Softening smaller corners', '<p>Window treatments are just as valuable in modest spaces. A pair of linen curtains can turn a window corner into a reading nook, framing armchairs and drawing the eye to the view while adding warmth and softness to the room.</p>', 'reading-nook-curtains-mirror'],
            ],
            'gallery' => ['sunlit-sofa-sheer-curtains', 'living-room-sheer-curtains-track-light', 'double-height-sheer-curtains', 'classic-dining-room-drapes', 'reading-nook-curtains-mirror'],
            'related' => ['living-room-decoration', 'bedroom-decoration', 'home-accessories-styling'],
        ],

        'furniture-styling' => [
            'name' => 'Furniture Styling & Arrangement',
            'icon' => 'layers',
            'short_description' => 'Expert furniture selection and arrangement that improves flow, comfort and balance, whether you are furnishing from scratch or reimagining the pieces you already own.',
            'hero_title' => 'Furniture Styling & Arrangement Services',
            'hero_description' => 'The right furniture, in the right place, changes everything. We plan, source and arrange pieces so your rooms feel balanced, comfortable and naturally inviting.',
            'hero' => 'living-room-timber-screen',
            'intro' => '<p>Furniture defines how a room is used and how it feels to move through it. Our furniture styling and arrangement service brings a designer\'s eye to scale, proportion and placement, so every piece earns its place and the room works as a whole.</p><p>We begin with a measured plan, testing different layouts to find the best balance of comfort, circulation and focal points. Sometimes the most effective change is simply rearranging what you already own; at other times a few carefully chosen additions — a larger rug, a pair of armchairs, a console or a screen to define a zone — make all the difference.</p><p>When new furniture is needed, we help you select pieces that suit both the space and your lifestyle, considering size, comfort, materials and how each item will age. From living rooms and dining spaces to studies and walk-in wardrobes, we make sure the final arrangement feels effortless.</p>',
            'included' => [
                ['Measured layout planning', 'Scaled plans exploring different arrangements before anything is moved or bought.'],
                ['Existing furniture review', 'Advice on what to keep, reposition, reupholster or replace.'],
                ['Furniture sourcing', 'Selection of sofas, tables, chairs, storage and statement pieces to suit the scheme.'],
                ['Zoning open-plan spaces', 'Rugs, screens and furniture groupings that define areas without closing them off.'],
                ['Studies & dressing rooms', 'Desks, shelving, islands and seating planned for focus and practicality.'],
                ['On-site arrangement', 'Placement and fine-tuning on the day, so the room feels right in person.'],
            ],
            'styles' => [
                ['Balanced Symmetry', 'Mirrored arrangements around a fireplace or bed for calm, classic order.'],
                ['Relaxed Asymmetry', 'Informal groupings with varied heights and forms for a lived-in feel.'],
                ['Defined Zones', 'Open-plan spaces divided into living, dining and working areas with clear purpose.'],
                ['Statement Pieces', 'A sculptural chair, console or table used as a deliberate focal point.'],
            ],
            'benefits' => [
                ['Better flow', 'Clear pathways and sensible groupings make rooms easier to use every day.'],
                ['More from what you own', 'Existing pieces are given new life through thoughtful placement.'],
                ['Confident purchasing', 'Scale and fit are checked on plan, reducing costly mistakes.'],
                ['Comfort built in', 'Seating heights, depths and distances are planned for genuine comfort.'],
            ],
            'materials' => [
                ['Solid & veneered timber', 'Walnut, oak and ash for tables, storage and joinery.'],
                ['Bouclé, linen & leather', 'Upholstery with texture and character for sofas and chairs.'],
                ['Marble & stone tops', 'Coffee, side and console tables with natural veining.'],
                ['Metal & lacquered finishes', 'Brass, bronze and lacquer details for refined contrast.'],
            ],
            'meta_title' => 'Furniture Styling & Arrangement',
            'meta_description' => 'Furniture styling and arrangement: measured layouts, sourcing, zoning for open-plan living and on-site placement for balanced rooms.',
            'sections' => [
                ['The art of the vignette', '<p>Some of the most memorable moments in a home are small: a console table with a lamp and a stack of books, an armchair angled towards the light, a pair of vases balanced against a mirror. We compose these vignettes deliberately, using contrasts of height, texture and form so that hallways, landings and corners feel as considered as the main rooms.</p><p>These arrangements also help connect spaces, carrying the palette and materials from one room to the next.</p>', 'console-table-armchair-vignette'],
                ['Scale and proportion', '<p>Furniture that is too small makes a room feel unfinished; too large and it feels cramped. We check every piece against the room\'s dimensions — sofa depths, coffee table heights, rug sizes and walkway widths — so that the arrangement feels generous yet comfortable, and each piece relates naturally to those around it.</p>', 'living-room-round-coffee-tables'],
            ],
            'gallery' => ['dining-room-ring-pendants', 'white-home-office-shelving', 'study-walnut-shelving-armchair', 'walk-in-closet-island', 'home-office-taupe-walls'],
            'related' => ['living-room-decoration', 'home-accessories-styling', 'full-home-decoration'],
        ],

        'lighting-ceiling-decor' => [
            'name' => 'Lighting & Ceiling Decor',
            'icon' => 'lamp',
            'short_description' => 'Layered lighting schemes, statement chandeliers and ceiling details such as coves and recesses that set the mood and bring architecture to every room.',
            'hero_title' => 'Lighting Design & Ceiling Decor Services',
            'hero_description' => 'Light shapes how a room feels more than almost anything else. We design layered lighting and ceiling details that make your home glow, day and night.',
            'hero' => 'luxury-dining-crystal-chandeliers',
            'intro' => '<p>Lighting is often the most overlooked part of a decor scheme, yet it has the greatest influence on atmosphere. Our lighting and ceiling decor service brings together ambient, task and accent lighting into a single considered plan, so each room can feel bright and practical when needed and soft and intimate when you want to relax.</p><p>Statement fittings play a key role. A sculptural ring chandelier in a double-height living room, a cluster of pendants over a dining table or a crystal chandelier in a bedroom can define a space and express its personality. We help you choose fittings in the right scale and finish, and position them precisely.</p><p>Ceilings themselves are an opportunity. Cove lighting, recessed details, coffers and concealed LED strips add architecture and a gentle wash of light, making rooms feel taller, calmer and more refined.</p>',
            'included' => [
                ['Layered lighting plan', 'Ambient, task and accent lighting mapped room by room.'],
                ['Statement fitting selection', 'Chandeliers, pendants and sculptural lights chosen for scale and character.'],
                ['Ceiling detail design', 'Cove, recess and coffer concepts with integrated LED lighting.'],
                ['Lamps & wall lights', 'Table, floor and wall lights that add warmth at eye level.'],
                ['Dimming & control advice', 'Recommendations for dimmers, scenes and colour temperature.'],
            ],
            'styles' => [
                ['Sculptural Modern', 'Ring, linear and organic-form fittings that act as art pieces.'],
                ['Crystal Classic', 'Chandeliers with glass or crystal detailing for formal elegance.'],
                ['Warm Industrial', 'Metal and enamel pendants with exposed bulbs and honest materials.'],
                ['Soft Architectural', 'Concealed cove and recessed lighting for a calm, seamless glow.'],
                ['Clustered Pendants', 'Groups of pendants at varied heights over dining tables and islands.'],
            ],
            'benefits' => [
                ['Atmosphere on demand', 'Layered, dimmable lighting adapts each room to the time of day and occasion.'],
                ['Practical where it matters', 'Task lighting is placed precisely for cooking, reading and working.'],
                ['Rooms that feel larger', 'Ceiling washes and coves make spaces feel taller and more open.'],
                ['A signature feature', 'A well-chosen statement light gives each room a memorable focal point.'],
            ],
            'materials' => [
                ['Glass & crystal', 'Blown glass, bubble glass and crystal for sparkle and soft diffusion.'],
                ['Brass, bronze & gold finishes', 'Warm metallics that glow when lit.'],
                ['Paper, linen & rattan shades', 'Natural shades that soften and warm the light.'],
                ['Integrated LED profiles', 'Discreet strip lighting for coves, shelves and recesses.'],
            ],
            'meta_title' => 'Lighting & Ceiling Decor Services',
            'meta_description' => 'Lighting and ceiling decor: layered lighting plans, statement chandeliers and pendants, cove and recessed ceiling details with soft LED light.',
            'sections' => [
                ['Statement lighting in large spaces', '<p>Generous rooms and double-height spaces need fittings with presence. We assess ceiling heights, sightlines and the furniture below to select chandeliers and pendants that feel proportionate from every angle, including from upper floors and landings.</p><p>Ring and linear chandeliers suit long living rooms and dining tables, while clusters and sculptural pieces bring drama to stairwells and entrance halls. Hanging heights are set carefully so light falls where it is needed and fittings never obstruct views.</p>', 'living-room-sculptural-chandelier'],
                ['Pendants over the table', '<p>Above a dining table, lighting becomes part of the meal. We size and space pendants in relation to the table\'s length, choose finishes that complement the chairs and walls, and add dimming so the mood can shift from family breakfast to an evening dinner with friends.</p>', 'dining-room-pendant-cluster'],
            ],
            'gallery' => ['living-room-statement-chandelier', 'dining-room-bubble-chandelier', 'marble-wall-ring-chandelier', 'dining-room-crystal-chandelier', 'dining-room-industrial-pendants'],
            'related' => ['living-room-decoration', 'bedroom-decoration', 'wall-decor-wallpaper'],
        ],

        'home-accessories-styling' => [
            'name' => 'Home Accessories & Styling',
            'icon' => 'gem',
            'short_description' => 'The finishing layer that makes a house feel like home: artwork, mirrors, ceramics, books, plants and textiles, curated and arranged with an editor\'s eye.',
            'hero_title' => 'Home Accessories & Interior Styling Services',
            'hero_description' => 'Accessories turn a well-designed room into a personal one. We curate and style art, objects, plants and textiles that bring warmth, character and polish.',
            'hero' => 'living-room-round-mirror-cream-sofa',
            'intro' => '<p>Even the most beautifully furnished room can feel incomplete without the final layer of styling. Our home accessories and styling service focuses on the details — artwork, mirrors, ceramics, books, plants, cushions and throws — that bring personality and warmth to a space.</p><p>We work with what you already own first, rediscovering favourite pieces and giving them a better setting. Then we source additional items to fill gaps, introducing texture, height and colour where the room needs it. Coffee tables, shelves, consoles, mantelpieces and sideboards are styled with a balance of order and ease, so they feel collected rather than staged.</p><p>Styling is also an excellent way to refresh a home without major works. A new arrangement of art, a change of cushions and throws for the season, or a few sculptural objects can make familiar rooms feel new again.</p>',
            'included' => [
                ['Styling assessment', 'A room-by-room review of what you own and where styling can make the most difference.'],
                ['Art & mirror placement', 'Selection, scale and hanging plans for artwork and decorative mirrors.'],
                ['Accessory sourcing', 'Ceramics, vases, sculptures, books, trays and decorative objects.'],
                ['Soft furnishing refresh', 'Cushions, throws and rugs to update colour and texture.'],
                ['Plants & greenery', 'Living and dried botanicals chosen to suit light and lifestyle.'],
                ['Shelf & surface styling', 'Composed arrangements for shelves, consoles, mantels and coffee tables.'],
            ],
            'styles' => [
                ['Collected & Personal', 'Treasured objects, books and art arranged with a relaxed, layered feel.'],
                ['Quiet Minimal', 'Few, carefully chosen pieces with plenty of breathing space.'],
                ['Organic & Earthy', 'Dried grasses, handmade ceramics, woven textures and natural tones.'],
                ['Gallery-Inspired', 'Statement art and sculpture given room to stand out.'],
            ],
            'benefits' => [
                ['Personality and warmth', 'Your home reflects your story, travels and tastes.'],
                ['A quick, impactful refresh', 'Styling can transform rooms without building work.'],
                ['Effortless-looking surfaces', 'Shelves and tables feel balanced and easy to maintain.'],
                ['Seasonal flexibility', 'Textiles and accessories can be updated as the seasons change.'],
            ],
            'materials' => [
                ['Handmade ceramics & glass', 'Vases, bowls and vessels with individual character.'],
                ['Original & printed artwork', 'Paintings, prints and photography framed to suit the room.'],
                ['Natural textiles', 'Wool, linen and cotton cushions and throws in layered textures.'],
                ['Woven & natural objects', 'Trays, baskets, timber and stone pieces that add tactility.'],
                ['Botanicals', 'Indoor plants, olive trees and dried arrangements.'],
            ],
            'meta_title' => 'Home Accessories & Styling Services',
            'meta_description' => 'Home accessories and interior styling: artwork, mirrors, ceramics, plants and textiles curated and arranged to bring warmth and character.',
            'sections' => [
                ['Styling the coffee table', '<p>The coffee table is one of the most-viewed surfaces in a home, and one of the easiest to get right with a few principles. We work in layers: a tray or a stack of books to ground the arrangement, a piece with height such as a vase of greenery, and a small sculptural object for interest. Varying shapes and textures keeps it engaging, while leaving open space keeps it practical for everyday use.</p>', 'coffee-table-styling-tray'],
                ['Art that anchors a room', '<p>A large-scale painting or a carefully composed group of works can set the colour story for an entire room. We help you choose art that resonates with you, then determine scale, framing and hanging height in relation to the furniture beneath it, so the piece feels anchored rather than floating.</p>', 'living-room-abstract-art'],
            ],
            'gallery' => ['styled-cosy-living-room', 'warm-living-room-pampas-art', 'living-room-shelves-paper-lanterns', 'living-room-fireplace-artwork', 'living-room-sculpture-statement-art'],
            'related' => ['furniture-styling', 'wall-decor-wallpaper', 'living-room-decoration'],
        ],

        'full-home-decoration' => [
            'name' => 'Full Home Decoration',
            'icon' => 'home',
            'short_description' => 'A complete, cohesive decoration service for the whole home, from entrance hall to bedrooms, with one connected design language and a single, coordinated process.',
            'hero_title' => 'Complete Full Home Decoration Services',
            'hero_description' => 'For new homes, renovations or complete refreshes, we design and deliver every room as part of one cohesive vision — managed from first concept to final styling.',
            'hero' => 'open-living-dining-marble',
            'intro' => '<p>Decorating an entire home is a significant undertaking, and the most rewarding results come from treating it as one connected project rather than a series of separate rooms. Our full home decoration service creates a single design language — palette, materials, furniture style and lighting approach — and then interprets it thoughtfully in each space.</p><p>Entrance halls, staircases and landings set the tone, so we give them the same care as the main living spaces. Living, dining and kitchen areas are designed to flow into one another, while bedrooms, bathrooms and studies are given their own character within the overall scheme.</p><p>Because we coordinate every element — wall finishes, curtains, furniture, lighting, accessories and installation — you benefit from a single point of contact and a clear, phased plan. Work can be scheduled room by room or delivered as one project, depending on your timeline and priorities.</p>',
            'included' => [
                ['Whole-home concept', 'A unified palette, material library and style direction for every room.'],
                ['Room-by-room design', 'Layouts, finishes, furniture and lighting tailored to each space within the overall vision.'],
                ['Entrance, stairs & circulation', 'Halls, staircases and landings designed to make a strong first impression.'],
                ['Phased planning', 'A clear schedule that can be delivered all at once or in stages.'],
                ['Supplier & installer coordination', 'One team managing ordering, deliveries and specialist trades.'],
                ['Complete styling & handover', 'Every room styled and ready to live in, with care guidance for finishes.'],
            ],
            'styles' => [
                ['Modern Marble', 'Polished stone floors, warm neutrals and statement lighting throughout.'],
                ['Warm Contemporary', 'Timber, linen and soft curves for relaxed, family-friendly elegance.'],
                ['Quiet Luxury', 'Understated palettes elevated by exceptional materials and craftsmanship.'],
                ['Classic Contemporary', 'Traditional proportions and detailing balanced with modern comfort.'],
            ],
            'benefits' => [
                ['One cohesive home', 'Rooms relate to each other, creating a sense of harmony from the front door onwards.'],
                ['A single point of contact', 'Design, sourcing and installation are coordinated by one team.'],
                ['Efficient decisions', 'A whole-home material palette simplifies choices and avoids clashes.'],
                ['Flexible delivery', 'Work can be phased to suit budgets, timelines and family life.'],
            ],
            'materials' => [
                ['Natural stone & marble', 'Floors, inlays, stairs and feature walls with lasting presence.'],
                ['Timber joinery & cladding', 'Warm walnut and oak details that carry through the home.'],
                ['Coordinated textiles', 'A fabric library used across curtains, upholstery and cushions.'],
                ['Architectural lighting', 'Staircase, cove and feature lighting integrated into the scheme.'],
                ['Indoor planting', 'Planters and greenery that soften stone and bring life to atriums and halls.'],
            ],
            'meta_title' => 'Full Home Decoration Services',
            'meta_description' => 'Full home decoration: one cohesive design for every room, from entrance hall to bedrooms, with coordinated sourcing, installation and styling.',
            'sections' => [
                ['First impressions: halls and staircases', '<p>The entrance hall is the introduction to your home, and the staircase is often its most sculptural element. We design these spaces with particular care — polished stone, lit treads, generous planting and carefully placed artwork can turn circulation areas into a memorable welcome.</p><p>Because these spaces connect every floor, they also act as the thread that ties the whole home\'s palette and materials together.</p>', 'marble-entrance-hall-staircase'],
                ['A connected palette, room to room', '<p>A whole-home scheme does not mean every room looks the same. We define a core palette of materials and tones, then vary its intensity — brighter and more open in living spaces, softer and warmer in bedrooms — so each room has its own mood while the home still feels unmistakably connected.</p>', 'white-marble-living-room'],
            ],
            'gallery' => ['marble-floor-living-room', 'staircase-atrium-indoor-planting', 'marble-lounge-dining-open-plan', 'marble-foyer-inlay', 'open-kitchen-marble-island'],
            'related' => ['living-room-decoration', 'furniture-styling', 'lighting-ceiling-decor'],
        ],
    ];

    public function run(): void
    {
        $services = [];
        $order = 0;

        foreach (self::SERVICES as $slug => $data) {
            $services[$slug] = $service = $this->upsertService($slug, $data, ++$order);
            $this->syncSections($service, $data['sections']);
            $this->syncGallery($service, $data['gallery']);
        }

        // Related services need every service to exist first.
        foreach (self::SERVICES as $slug => $data) {
            $services[$slug]->related()->sync(array_map(fn ($s) => $services[$s]->id, $data['related']));
        }
    }

    protected function upsertService(string $slug, array $data, int $order): Service
    {
        return Service::withTrashed()->updateOrCreate(['slug' => $slug], [
            'name' => $data['name'],
            'icon' => $data['icon'],
            'short_description' => $data['short_description'],
            'hero_title' => $data['hero_title'],
            'hero_description' => $data['hero_description'],
            'hero_media_id' => $this->media($data['hero']),
            'og_media_id' => $this->media($data['hero']),
            'intro' => $data['intro'],
            'included' => $this->pairs($data['included']),
            'styles' => $this->pairs($data['styles']),
            'benefits' => $this->pairs($data['benefits']),
            'materials' => $this->pairs($data['materials']),
            'process' => null, // uses the global process_steps setting
            'meta_title' => $data['meta_title'].self::TITLE_SUFFIX,
            'meta_description' => $data['meta_description'],
            'is_featured' => $order <= self::FEATURED_COUNT,
            'is_published' => true,
            'sort_order' => $order,
            'deleted_at' => null,
        ]);
    }

    protected function syncSections(Service $service, array $sections): void
    {
        $service->sections()->delete();

        foreach ($sections as $i => [$heading, $body, $mediaSlug]) {
            $service->sections()->create([
                'heading' => $heading,
                'body' => $body,
                'media_id' => $mediaSlug ? $this->media($mediaSlug) : null, // text-only blocks are allowed
                'sort_order' => $i + 1,
            ]);
        }
    }

    protected function syncGallery(Service $service, array $slugs): void
    {
        $sync = [];
        foreach (array_values($slugs) as $i => $slug) {
            $sync[$this->media($slug)] = ['sort_order' => $i + 1];
        }
        $service->gallery()->sync($sync);
    }

    /** @param  array<int, array{0: string, 1: string}>  $pairs */
    protected function pairs(array $pairs): array
    {
        return array_map(fn (array $p) => ['title' => $p[0], 'text' => $p[1]], $pairs);
    }
}
