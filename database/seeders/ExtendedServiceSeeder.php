<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

/**
 * The 10 additional services (display order 11–20), rendered by the same services/show template.
 *
 * Create-only: a service whose slug already exists is skipped, so running this seeder on the live
 * site never overwrites edits made in the admin dashboard. Copy describes each service, its approach
 * and options only — no claims about past results, clients or figures. Portfolio links point only at
 * existing projects whose photos genuinely show that kind of space.
 *
 * Pairs inside arrays are [title, text]; sections are [heading, html body, media slug|null];
 * faqs are [question, answer]; related/projects/articles are slugs (missing ones are ignored).
 */
class ExtendedServiceSeeder extends ServiceSeeder
{
    private const FIRST_SORT_ORDER = 11;

    private const SERVICES = [
        'dining-room-decoration' => [
            'name' => 'Dining Room Decoration',
            'icon' => 'dining',
            'short_description' => 'Dining rooms designed for long, comfortable meals and memorable evenings, with the right table and chairs, a statement light at the centre and finishes that feel special.',
            'hero_title' => 'Dining Room Decoration Services',
            'hero_description' => 'Whether you host often or gather mainly as a family, we create dining rooms that feel warm, generous and quietly elegant from the first course to the last.',
            'hero' => 'dining-room-sculptural-mirrors',
            'intro' => '<p>A dining room is about more than a table. It is where celebrations happen, where conversations stretch late into the evening and where everyday meals become a chance to slow down. Our dining room decoration service shapes the space around those moments, so it feels inviting whether it seats four or fourteen.</p><p>We start with proportion: a table sized to the room and to how you entertain, chairs that are comfortable for a long meal, and enough clearance to move around easily. The light above the table is treated as the room\'s focal point, and we often coordinate it with our <a href="/services/lighting-ceiling-decor">lighting and ceiling decor</a> service so pendants, wall lights and dimming all work together.</p><p>Walls, window dressings, a sideboard for serving and display, and the finishing details on the table complete the room. In open-plan homes we also make sure the dining zone sits naturally between kitchen and living areas, connected by palette and materials.</p>',
            'included' => [
                ['Table & seating plan', 'Table shape, size and seating capacity matched to the room, with comfortable clearances around every chair.'],
                ['Statement lighting', 'A chandelier or pendant arrangement scaled to the table, hung at the right height and paired with dimming.'],
                ['Dining chair selection', 'Upholstered, timber or mixed seating chosen for comfort through long meals and easy upkeep.'],
                ['Sideboard & display', 'Storage for tableware and a styled surface for serving, art, lamps and seasonal pieces.'],
                ['Walls & window dressing', 'Feature walls, mirrors and curtains that add warmth, softness and better acoustics.'],
                ['Table styling', 'Runners, centrepieces and tableware suggestions so the table looks finished between meals.'],
            ],
            'styles' => [
                ['Formal Elegance', 'Crystal or glass chandeliers, upholstered chairs and floor-length drapes for occasions to remember.'],
                ['Warm Contemporary', 'Oak or walnut tables, sculptural pendants and soft neutral upholstery.'],
                ['Marble & Brass', 'Stone-topped tables, metallic bases and gold accents for a polished, luxurious feel.'],
                ['Relaxed Family Dining', 'Durable finishes, a generous table and comfortable seating made for everyday use.'],
            ],
            'benefits' => [
                ['Made for gathering', 'Seating, spacing and lighting are planned so guests are comfortable for the whole evening.'],
                ['A true focal point', 'A well-scaled statement light gives the room presence and sets the mood instantly.'],
                ['Practical luxury', 'Wipeable fabrics, durable surfaces and protected table tops keep the room easy to live with.'],
                ['Connected to the home', 'The dining scheme flows naturally into the kitchen and living spaces around it.'],
            ],
            'materials' => [
                ['Solid timber & veneer tables', 'Oak, walnut and ash tables in rectangular, round and oval forms.'],
                ['Marble & sintered stone tops', 'Durable, veined surfaces that make the table a centrepiece.'],
                ['Performance upholstery', 'Stain-resistant fabrics and leathers for dining chairs and banquettes.'],
                ['Glass, crystal & metal fittings', 'Chandeliers and pendants that sparkle and cast a flattering light.'],
                ['Rugs sized for dining', 'Low-pile rugs large enough for chairs to slide back without catching.'],
            ],
            'meta_title' => 'Dining Room Decoration Services',
            'meta_description' => 'Dining room decoration for memorable meals: table and seating plans, statement lighting, sideboards, walls and styling for family dining and entertaining.',
            'sections' => [
                ['The light above the table', '<p>Nothing defines a dining room like the fitting above the table. We choose its size in relation to the table\'s length and width, then set the hanging height so the light falls on the table without blocking the view across it. A long table may suit a linear chandelier or a row of pendants; a round table often calls for a single sculptural piece.</p><p>Dimming is essential. Bright light helps for family breakfasts and homework, while a soft, low glow turns the same room into an intimate setting for dinner with friends.</p>', 'dining-room-gold-mesh-chandelier'],
                ['Comfort for long evenings', '<p>Beautiful chairs that are uncomfortable after twenty minutes spoil a dining room. We look closely at seat height, depth and back support, and at arms that slide under the table. Upholstered or padded seats, a rug that chairs can move over smoothly and curtains that soften the acoustics all help guests settle in and stay.</p>', 'classic-dining-room-drapes'],
            ],
            'gallery' => ['dining-room-bubble-chandelier', 'dining-room-ring-pendants', 'dining-room-pendant-cluster', 'dining-room-crystal-chandelier', 'luxury-dining-crystal-chandeliers'],
            'related' => ['kitchen-decoration', 'lighting-ceiling-decor', 'curtains-window-treatments'],
            'projects' => ['statement-lit-dining-rooms'],
            'articles' => ['layered-lighting-room-by-room-guide', 'how-to-create-a-luxury-home-look'],
            'faqs' => [
                ['What size dining table suits my room?', 'We measure the room and allow comfortable space behind every chair so people can sit down and get up easily. From there we recommend a table shape and size that suits both the room and the number of people you usually seat.'],
                ['How high should a pendant hang above a dining table?', 'The right height depends on the fitting, the table and your ceiling. We set it so the light falls on the table without blocking the view across it, and check it in person during installation.'],
                ['Can a dining area in an open-plan space feel like its own room?', 'Yes. A rug, a statement light and a consistent palette can define the dining zone clearly while keeping it connected to the kitchen and living areas around it.'],
            ],
        ],

        'home-office-decoration' => [
            'name' => 'Home Office Decoration',
            'icon' => 'desk',
            'short_description' => 'Calm, well-organised home offices that help you focus, with a comfortable desk set-up, good natural and task lighting, smart storage and a backdrop that looks great on video calls.',
            'hero_title' => 'Home Office Decoration Services',
            'hero_description' => 'We design home offices and studies that make focused work easier and feel like a natural part of your home rather than a desk squeezed into a corner.',
            'hero' => 'home-office-oak-joinery-glass-pendants',
            'intro' => '<p>Working from home has changed what we need from our living spaces. A good home office supports concentration, keeps paperwork and equipment out of sight, and still feels like part of a beautiful home. Our home office decoration service balances all three, whether you have a dedicated room, a guest bedroom that doubles as a study or a corner of the living area.</p><p>We start with how you work: the hours you spend at the desk, the equipment you use, how often you take calls and whether you need space for meetings, reading or creative work. The desk position is chosen to make the most of daylight without glare on screens, and seating is selected for genuine comfort through a long working day.</p><p>Storage and lighting then do the heavy lifting. Built-in shelving, closed cabinets and cable management keep the room calm, while layered lighting, often planned with our <a href="/services/furniture-styling">furniture styling and arrangement</a> team, keeps the space bright for work and warm in the evening.</p>',
            'included' => [
                ['Desk position & layout', 'A layout that uses daylight well, avoids screen glare and leaves room to move and stretch.'],
                ['Ergonomic seating advice', 'Chairs and desk heights selected for comfort and posture through long working days.'],
                ['Storage & shelving', 'Closed cabinets for clutter, open shelves for books and display, and discreet cable management.'],
                ['Task & ambient lighting', 'Desk lamps, pendants and shelf lighting that keep you focused by day and relaxed by evening.'],
                ['Video-call backdrop', 'A styled wall or shelf behind your seat so calls look professional and personal.'],
                ['Acoustic comfort', 'Rugs, curtains and upholstered pieces that soften echo and outside noise.'],
            ],
            'styles' => [
                ['Warm Study', 'Walnut joinery, leather seating and lit shelving for a library-like atmosphere.'],
                ['Clean Minimal', 'White or pale surfaces, hidden storage and very little visual noise.'],
                ['Executive Classic', 'A substantial desk, built-in bookcases and refined traditional detailing.'],
                ['Flexible Guest Study', 'A workspace that folds away neatly when the room is needed for visitors.'],
            ],
            'benefits' => [
                ['Better focus', 'A calm, organised room with good light makes it easier to settle into deep work.'],
                ['Work–life separation', 'Thoughtful storage lets you close the working day and enjoy the room again.'],
                ['Comfort that lasts', 'Ergonomic seating and desk heights reduce strain over long hours.'],
                ['Professional presence', 'A considered backdrop makes video calls look polished without effort.'],
            ],
            'materials' => [
                ['Timber joinery', 'Oak and walnut desks, shelving and cabinetry that age beautifully.'],
                ['Stone & leather details', 'Desk tops, inlays and leather accents for a tactile, refined finish.'],
                ['Integrated LED shelf lighting', 'Soft light that brings shelving to life and reduces eye strain.'],
                ['Acoustic textiles', 'Wool rugs, lined curtains and upholstered panels that absorb sound.'],
            ],
            'meta_title' => 'Home Office Decoration Services',
            'meta_description' => 'Home office decoration for focused work: desk layout, ergonomic seating, storage, task lighting and a polished video-call backdrop that suits your home.',
            'sections' => [
                ['Shelving that works hard', '<p>Built-in shelving can turn a plain room into a study with real character. We combine closed cupboards at low level for files and equipment with open shelves above for books, objects and art. Integrated lighting along each shelf adds depth in the evening and provides a gentle background glow on screen.</p><p>Keeping a little empty space on every shelf stops the wall from feeling crowded and makes the arrangement easy to maintain.</p>', 'backlit-shelving-home-office'],
                ['A desk with a view', '<p>Wherever possible, we position the desk to look towards daylight or a view rather than a blank wall. Side light from a window is ideal for screens, and a glimpse of greenery can make long working days feel lighter. Blinds or sheer curtains then give you control over glare as the sun moves.</p>', 'study-stone-desk-garden'],
            ],
            'gallery' => ['white-home-office-shelving', 'home-office-lit-shelves', 'home-office-taupe-walls', 'walnut-study-garden-view', 'classic-white-study'],
            'related' => ['furniture-styling', 'lighting-ceiling-decor', 'small-space-apartment-decoration'],
            'projects' => ['walnut-stone-garden-study', 'lit-shelving-home-offices'],
            'articles' => ['layered-lighting-room-by-room-guide'],
            'faqs' => [
                ['Can you create a workspace in a room that has another use?', 'Yes. We often design studies within guest rooms, living areas or bedrooms, using furniture, storage and lighting so the workspace feels intentional and can be tidied away when the room is used for something else.'],
                ['Where is the best place to put a desk?', 'Ideally where daylight comes from the side, which reduces glare on screens, and where you have a pleasant view. We look at your room\'s windows, sockets and circulation before recommending a position.'],
                ['Can you make my video-call background look better?', 'We style the wall or shelving behind your seat with a balance of books, objects, art and soft lighting, so calls look professional while still feeling personal.'],
            ],
        ],

        'kids-room-nursery-decoration' => [
            'name' => 'Kids Room & Nursery Decoration',
            'icon' => 'crib',
            'short_description' => 'Safe, soothing nurseries and playful children\'s rooms that grow with your family, combining gentle colour, clever storage, soft textures and furniture designed for every stage.',
            'hero_title' => 'Kids Room & Nursery Decoration Services',
            'hero_description' => 'From a calm first nursery to a room for a growing child, we design spaces that are gentle, practical and full of personality — and ready to adapt as your child grows.',
            'hero' => 'pastel-nursery-timber-crib',
            'intro' => '<p>A child\'s room has to do a lot: it is a place to sleep, play, read, learn and, as they grow, to express who they are. Our kids room and nursery decoration service creates rooms that feel soothing and safe while staying practical for busy family life.</p><p>For nurseries, we focus on calm colours, soft layered lighting for night feeds, comfortable seating for parents and storage that keeps essentials within easy reach. For older children, we plan zones for sleep, play and study, with storage they can use themselves to make tidying easier.</p><p>We think ahead, too. Furniture that converts, neutral foundations with playful accents that can be changed, and wall treatments that are easy to refresh mean the room can grow with your child instead of being redone every few years. Our <a href="/services/bedroom-decoration">bedroom decoration</a> approach to restful sleep applies here as well.</p>',
            'included' => [
                ['Age-appropriate layout', 'Zones for sleeping, playing, reading and studying, planned around your child\'s stage.'],
                ['Gentle colour scheme', 'Soothing base colours with playful accents that are easy to update later.'],
                ['Storage children can use', 'Low shelves, baskets and wardrobes sized so little ones can help tidy up.'],
                ['Soft, layered lighting', 'Dimmable lamps and night lights for bedtime, plus brighter light for play and homework.'],
                ['Textiles & window dressing', 'Blackout curtains, soft rugs and washable fabrics for comfort and better sleep.'],
                ['Wall decor & personal touches', 'Murals, wallpaper, art and display spaces that reflect your child\'s interests.'],
            ],
            'styles' => [
                ['Soft Pastel Nursery', 'Blush, sage and cream tones with natural timber and plush textures.'],
                ['Natural & Montessori-Inspired', 'Low furniture, natural materials and open storage that encourage independence.'],
                ['Playful Adventure', 'Themed walls, reading nooks and bold accents for imaginative play.'],
                ['Grow-With-Me', 'Neutral foundations and convertible furniture that transition from toddler to teen.'],
            ],
            'benefits' => [
                ['Better bedtimes', 'Calming colours, blackout options and soft lighting support settled sleep.'],
                ['Easier tidying', 'Storage at child height turns putting toys away into a simple routine.'],
                ['Designed to adapt', 'Convertible furniture and changeable accents extend the life of the room.'],
                ['Family-friendly finishes', 'Washable, durable materials stand up to everyday spills and play.'],
            ],
            'materials' => [
                ['Solid timber furniture', 'Sturdy cribs, beds and shelving in natural or painted finishes.'],
                ['Washable fabrics', 'Removable covers and easy-care textiles for cushions, chairs and bedding.'],
                ['Wipeable wall finishes', 'Scrubbable paints and durable wallpapers that are easy to keep fresh.'],
                ['Soft rugs & play mats', 'Cushioned, low-pile surfaces that are comfortable for floor play.'],
                ['Blackout linings', 'Curtains and blinds that darken the room for naps and early nights.'],
            ],
            'meta_title' => 'Kids Room & Nursery Decoration',
            'meta_description' => 'Kids room and nursery decoration: calming colour schemes, child-height storage, soft lighting and convertible furniture for rooms that grow with your child.',
            'sections' => [
                ['A nursery designed for calm', '<p>The first months with a baby are full of night feeds and early mornings, so a nursery should feel peaceful at any hour. We favour soft, muted colours, dimmable lamps rather than bright overhead light, and blackout curtains layered with sheers. A comfortable chair for feeding, a changing area with everything within reach and a soft rug underfoot make the routine easier for parents.</p>', null],
                ['Rooms that grow with your child', '<p>Children\'s tastes change quickly, so we build each room on a timeless foundation — neutral walls, quality furniture and good storage — and add personality through elements that are easy to swap: bedding, cushions, art, a mural or a wallpapered feature wall. Cots that convert to beds and desks that adjust in height help the room keep pace as your child grows.</p>', null],
            ],
            'gallery' => ['pastel-nursery-timber-crib'],
            'related' => ['bedroom-decoration', 'wall-decor-wallpaper', 'curtains-window-treatments'],
            'projects' => [],
            'articles' => ['how-to-decorate-a-small-bedroom', 'wallpaper-vs-paint'],
            'faqs' => [
                ['Can you design a room that works for siblings sharing?', 'Yes. We plan shared rooms so each child has their own sleeping area, storage and a personal touch, while the scheme as a whole feels cohesive and calm.'],
                ['How do you keep a child\'s room safe?', 'We choose sturdy furniture, recommend anchoring tall pieces to the wall, keep cords and small items out of reach and select finishes suitable for children\'s rooms. Safety is considered at every stage of the design.'],
                ['Will the room still work as my child gets older?', 'We design with the future in mind, using convertible furniture, neutral foundations and accents that are easy to change, so the room can be updated rather than completely redone.'],
            ],
        ],

        'balcony-terrace-decoration' => [
            'name' => 'Balcony & Terrace Decoration',
            'icon' => 'plant',
            'short_description' => 'Balconies, terraces and rooftop spaces turned into inviting outdoor rooms, with weather-ready seating, planting, shade, soft lighting and textiles that extend your living space outside.',
            'hero_title' => 'Balcony & Terrace Decoration Services',
            'hero_description' => 'However large or small your outdoor space, we design balconies and terraces that feel like a natural extension of your home — for morning coffee, quiet evenings and entertaining.',
            'hero' => 'balcony-lounge-string-lights',
            'intro' => '<p>An outdoor space, even a narrow balcony, can become one of the most loved areas of a home. Our balcony and terrace decoration service treats it as a true outdoor room, with the same care for layout, comfort and atmosphere that we bring to the spaces inside.</p><p>We begin with practicalities: the size and shape of the space, how much sun and wind it receives, weight and drainage considerations, and how you would like to use it. A compact balcony might call for built-in bench seating and wall-mounted planters, while a larger terrace could hold separate lounging and dining areas.</p><p>Materials are chosen to cope with the weather, and lighting is planned so the space is just as inviting after dark. Planting, outdoor rugs, cushions and lanterns then soften the hard surfaces and connect the outdoor room to the interior palette, often coordinated with our <a href="/services/home-accessories-styling">home accessories and styling</a> service.</p>',
            'included' => [
                ['Outdoor layout plan', 'Seating, dining and planting zones arranged to suit the size, shape and views of the space.'],
                ['Weather-ready furniture', 'Lounge seating, dining sets and built-in benches in materials made for outdoor use.'],
                ['Planting design', 'Planters, vertical greenery and plant choices suited to your light, wind and care routine.'],
                ['Outdoor lighting', 'String lights, lanterns and low-level lighting for a warm atmosphere after sunset.'],
                ['Shade & privacy', 'Canopies, screens, slatted panels and planting that provide shelter and seclusion.'],
                ['Outdoor textiles', 'Weather-resistant cushions, throws and rugs that add comfort and colour.'],
            ],
            'styles' => [
                ['Urban Retreat', 'Built-in timber seating, vertical planting and string lights for compact city balconies.'],
                ['Mediterranean Terrace', 'Terracotta, olive trees, natural textures and relaxed dining under shade.'],
                ['Modern Rooftop', 'Low lounge furniture, clean planters and statement lighting with open views.'],
                ['Garden Lounge', 'Lush planting, soft furnishings and a pergola for a sheltered, green escape.'],
            ],
            'benefits' => [
                ['More living space', 'A well-designed balcony or terrace adds a usable room to your home.'],
                ['Enjoyed day and night', 'Shade by day and soft lighting by night extend how long the space is used.'],
                ['Built for the weather', 'Outdoor-grade materials keep the space looking good season after season.'],
                ['Connected inside and out', 'A shared palette makes indoor and outdoor rooms feel like one home.'],
            ],
            'materials' => [
                ['Hardwood & composite decking', 'Warm, durable flooring that handles sun and rain.'],
                ['Teak, aluminium & rope furniture', 'Outdoor furniture that resists weathering and stays comfortable.'],
                ['Solution-dyed outdoor fabrics', 'Cushions and rugs that resist fading, moisture and mildew.'],
                ['Lightweight planters', 'Planters suited to balconies where weight is a consideration.'],
                ['Outdoor-rated lighting', 'Weatherproof string lights, lanterns and fittings.'],
            ],
            'meta_title' => 'Balcony & Terrace Decoration',
            'meta_description' => 'Balcony and terrace decoration: outdoor seating, planting, lighting, shade and weather-ready textiles that turn any outdoor space into an inviting room.',
            'sections' => [
                ['Small balconies, big possibilities', '<p>On a compact balcony every centimetre counts. Built-in bench seating along a wall frees up floor space, wall-mounted planters bring greenery without crowding the floor, and a small table that folds or nests keeps the layout flexible. Warm string lights overhead and a soft outdoor rug underfoot make the space feel like a cosy room rather than a ledge.</p>', null],
                ['Terraces made for gathering', '<p>Larger terraces can hold distinct areas — a lounge for relaxing and a dining table for meals outdoors. A pergola or canopy provides shade and a sense of enclosure, while planting softens edges and adds privacy. Low-level and overhead lighting together make the terrace just as inviting on warm evenings as it is in the afternoon sun.</p>', 'garden-lawn-pergola-dusk'],
            ],
            'gallery' => ['balcony-lounge-string-lights', 'garden-lawn-pergola-dusk', 'garden-water-feature-lighting'],
            'related' => ['home-accessories-styling', 'lighting-ceiling-decor', 'small-space-apartment-decoration'],
            'projects' => [],
            'articles' => ['affordable-ways-to-refresh-your-home'],
            'faqs' => [
                ['Can a very small balcony be decorated well?', 'Yes. Built-in or slim seating, vertical planting, wall-mounted lighting and a compact table can make even a narrow balcony comfortable and inviting.'],
                ['Which furniture materials last best outdoors?', 'Teak, powder-coated aluminium, rope weaves and solution-dyed fabrics generally cope well outdoors. We recommend materials based on how exposed your balcony or terrace is to sun and rain.'],
                ['Do you consider building rules for balconies?', 'We take weight, drainage and any building or community guidelines into account when planning planters, furniture and fixtures, and recommend checking specific rules with your building management where needed.'],
            ],
        ],

        'entryway-hallway-decoration' => [
            'name' => 'Entryway & Hallway Decoration',
            'icon' => 'door',
            'short_description' => 'Entrances, foyers and hallways that make a beautiful first impression and work hard every day, with smart storage, flattering lighting, mirrors, art and durable finishes.',
            'hero_title' => 'Entryway & Hallway Decoration Services',
            'hero_description' => 'The entrance sets the tone for your whole home. We design entryways and hallways that welcome you in, keep everyday clutter in check and connect every room beyond.',
            'hero' => 'entryway-console-round-mirror',
            'intro' => '<p>Your entryway is the first space guests see and the one you pass through most often. It needs to make a lasting impression while handling the practical realities of keys, bags, shoes and coats. Our entryway and hallway decoration service brings both together.</p><p>We start with storage, because a clutter-free entrance instantly feels more welcoming. Concealed cupboards, a console with drawers, a bench with space beneath or well-placed hooks give everything a home. A mirror helps with a final check on the way out and reflects light deeper into the space.</p><p>Hallways and corridors are often overlooked, yet they connect every room. Lighting, art, runners and wall treatments, sometimes developed with our <a href="/services/wall-decor-wallpaper">wall decor and wallpaper</a> service, turn them from passageways into spaces with character that carry your home\'s palette from room to room.</p>',
            'included' => [
                ['Entrance storage', 'Consoles, cupboards, benches and hooks that keep keys, shoes and coats organised.'],
                ['Mirror & console styling', 'A mirror and console arrangement that is practical and makes a strong first impression.'],
                ['Hallway lighting', 'Pendants, wall lights and picture lights that make corridors feel bright and welcoming.'],
                ['Wall treatments & art', 'Panelling, wallpaper and art that give circulation spaces character.'],
                ['Durable flooring & runners', 'Hard-wearing floors, entrance mats and runners for busy routes.'],
                ['Staircase & landing styling', 'Art, lighting and planting that connect each floor of the home.'],
            ],
            'styles' => [
                ['Grand Foyer', 'Polished stone, statement lighting and a sculptural centrepiece for a dramatic welcome.'],
                ['Modern Minimal', 'Concealed storage, a slim console and a single striking mirror.'],
                ['Warm Textural', 'Fluted timber, natural stone and dried botanicals for a soft, organic entrance.'],
                ['Gallery Hallway', 'A curated run of art with picture lighting along the corridor.'],
            ],
            'benefits' => [
                ['A lasting first impression', 'The entrance introduces your home\'s style from the moment the door opens.'],
                ['Clutter under control', 'Smart storage keeps daily essentials organised and out of sight.'],
                ['Brighter circulation', 'Mirrors and layered lighting make narrow halls feel open and inviting.'],
                ['A connected home', 'Hallways carry the palette and materials between rooms.'],
            ],
            'materials' => [
                ['Natural stone & porcelain', 'Hard-wearing floors that handle heavy foot traffic beautifully.'],
                ['Fluted & slatted timber', 'Wall panels that add warmth, texture and depth.'],
                ['Statement mirrors', 'Round, arched and framed mirrors that reflect light and space.'],
                ['Durable runners & mats', 'Woven and low-pile rugs designed for busy routes.'],
                ['Glass & metal pendants', 'Lighting that creates a welcoming glow from the threshold.'],
            ],
            'meta_title' => 'Entryway & Hallway Decoration',
            'meta_description' => 'Entryway and hallway decoration: entrance storage, consoles and mirrors, lighting, wall treatments and runners that create a welcoming first impression.',
            'sections' => [
                ['A welcome that lasts', '<p>In a generous foyer, materials do much of the talking. Polished stone floors with an inlay border, a view through to the main living spaces and a carefully chosen light overhead give guests an immediate sense of the home. We keep the space uncluttered so architecture and finishes can be appreciated, adding a statement piece or planting where it draws the eye.</p>', 'marble-foyer-inlay'],
                ['Small hallways, smart choices', '<p>Narrow hallways benefit from light colours, a mirror to bounce light and wall lights rather than bulky floor pieces. A slim console or floating shelf offers a landing spot for keys, while a runner adds warmth underfoot. Artwork hung at a consistent height draws you along the corridor and makes the walk from room to room a pleasure.</p>', 'console-table-armchair-vignette'],
            ],
            'gallery' => ['marble-entrance-hall-staircase', 'staircase-atrium-indoor-planting', 'marble-lounge-dining-open-plan'],
            'related' => ['wall-decor-wallpaper', 'lighting-ceiling-decor', 'full-home-decoration'],
            'projects' => ['marble-entrance-hall-staircase'],
            'articles' => ['wallpaper-vs-paint', 'how-to-create-a-luxury-home-look'],
            'faqs' => [
                ['How can I make a narrow hallway feel bigger?', 'Light colours, a well-placed mirror, wall lights instead of floor lamps and a slim console or floating shelf all help. We tailor these choices to your hallway\'s proportions and natural light.'],
                ['What storage works best in an entryway?', 'It depends on your household. Concealed cupboards suit busy families, while a console with drawers or a bench with baskets works well in smaller entrances. We plan storage around what you need to keep by the door.'],
                ['Can you style a staircase and landing as well?', 'Yes. Staircases and landings connect every floor, and art, lighting, runners and planting can make them feel like part of the design rather than just a route between rooms.'],
            ],
        ],

        'walk-in-closet-dressing-room-styling' => [
            'name' => 'Walk-in Closet & Dressing Room Styling',
            'icon' => 'hanger',
            'short_description' => 'Boutique-style walk-in closets and dressing rooms with tailored storage, lit wardrobes, a central island, a vanity and seating, designed to make getting ready a pleasure.',
            'hero_title' => 'Walk-in Closet & Dressing Room Styling',
            'hero_description' => 'We design walk-in closets and dressing rooms that keep everything beautifully organised and visible, with lighting, finishes and comforts that feel like a private boutique.',
            'hero' => 'walk-in-closet-pendants',
            'intro' => '<p>A walk-in closet or dressing room is one of the most personal spaces in the home. It should make getting ready each morning calm and enjoyable, with everything easy to find and a setting that feels special. Our walk-in closet and dressing room styling service creates exactly that.</p><p>Planning starts with an honest look at your wardrobe: how many long and short hanging items you have, how you store shoes, bags and accessories, and what you would like on display. From there we plan hanging, shelving, drawers and specialist storage, along with a central island, a vanity or a seating area if the space allows.</p><p>Lighting transforms a dressing room. Integrated strips inside wardrobes and shelves, a statement light overhead and flattering light around the mirror make colours true and the space inviting. Finishes and soft furnishings are then coordinated with the adjoining bedroom, as part of our wider <a href="/services/bedroom-decoration">bedroom decoration</a> approach.</p>',
            'included' => [
                ['Wardrobe audit & planning', 'A storage plan based on your actual clothing, shoes, bags and accessories.'],
                ['Custom storage layout', 'Hanging, shelving, drawers and specialist inserts arranged for easy access.'],
                ['Island & vanity design', 'A central island, dressing table or vanity where space allows.'],
                ['Integrated wardrobe lighting', 'Lit rails, shelves and mirrors so colours look true and everything is visible.'],
                ['Seating & mirrors', 'An ottoman, bench or armchair and full-length mirrors for comfortable dressing.'],
                ['Display styling', 'Curated arrangements for favourite bags, shoes, jewellery and accessories.'],
            ],
            'styles' => [
                ['Boutique Luxe', 'Glass-fronted cabinets, a marble-topped island and a statement chandelier.'],
                ['Warm Oak', 'Natural timber shelving, soft lighting and woven baskets.'],
                ['Soft Contemporary', 'Matte cream cabinetry, a backlit round mirror and an upholstered pouf.'],
                ['Compact Dressing Area', 'A space-efficient layout for a converted bedroom corner or alcove.'],
            ],
            'benefits' => [
                ['Everything in view', 'Thoughtful storage and lighting mean you can see and reach what you own.'],
                ['Calmer mornings', 'An organised dressing room makes getting ready quicker and more enjoyable.'],
                ['Care for what you own', 'Proper hanging and storage help clothes, shoes and bags last longer.'],
                ['A personal retreat', 'Seating, mirrors and finishing touches create a space to linger.'],
            ],
            'materials' => [
                ['Oak & lacquered cabinetry', 'Durable, elegant finishes for wardrobes and shelving.'],
                ['Glass doors & fronts', 'Display storage that protects items while keeping them visible.'],
                ['Stone & marble island tops', 'Smooth, durable surfaces for folding and display.'],
                ['Velvet-lined drawers & trays', 'Soft inserts for jewellery, watches and accessories.'],
                ['LED strip & mirror lighting', 'Even, flattering light inside wardrobes and around mirrors.'],
            ],
            'meta_title' => 'Walk-in Closet & Dressing Room Styling',
            'meta_description' => 'Walk-in closet and dressing room styling: tailored wardrobe storage, lit shelving, islands, vanities and seating for a calm, boutique-style space to get ready.',
            'sections' => [
                ['Light that shows every colour', '<p>Good lighting is what makes a dressing room feel like a boutique. We place LED strips along rails and shelves so every item is lit, add a soft overhead light for atmosphere and frame the mirror with even, flattering light. Warm-white tones keep the space inviting while still showing colours accurately when you choose an outfit.</p>', 'walk-in-closet-lit-vanity-mirror'],
                ['A dressing room to linger in', '<p>Where space allows, a central island adds storage and a surface for folding, packing or displaying accessories. A dressing table, an armchair or a window seat turns the room from storage into somewhere you want to spend time, and a statement chandelier gives it a final touch of glamour.</p>', 'dressing-room-chandelier'],
            ],
            'gallery' => ['walk-in-closet-island', 'walk-in-closet-window-seat', 'walk-in-closet-garden-view'],
            'related' => ['bedroom-decoration', 'furniture-styling', 'lighting-ceiling-decor'],
            'projects' => ['oak-marble-walk-in-dressing-room'],
            'articles' => ['how-to-create-a-luxury-home-look'],
            'faqs' => [
                ['Do I need a separate room for a walk-in closet?', 'Not necessarily. A dressing area can be created in part of a bedroom, a spare room or an alcove. We design the layout around the space you have.'],
                ['How do you plan storage for my wardrobe?', 'We review what you own — long and short hanging, folded items, shoes, bags and accessories — and design the hanging, shelving and drawers around it, leaving some room for your wardrobe to grow.'],
                ['Can lighting be built into the wardrobes?', 'Yes. Integrated LED lighting along rails, shelves and inside cabinets is one of the options we design, making everything easy to see and adding a soft glow to the room.'],
            ],
        ],

        'small-space-apartment-decoration' => [
            'name' => 'Small Space & Apartment Decoration',
            'icon' => 'maximize',
            'short_description' => 'Smart, stylish decoration for apartments, studios and compact rooms, using clever layouts, multi-functional furniture, light-enhancing finishes and hidden storage to make every metre count.',
            'hero_title' => 'Small Space & Apartment Decoration Services',
            'hero_description' => 'A small home can feel generous, calm and beautifully finished. We design apartments and compact rooms that make the most of every metre without compromising on style.',
            'hero' => 'minimal-beige-living-room',
            'intro' => '<p>Living in a smaller home means every decision matters. Furniture needs to be the right size, storage needs to be clever, and the scheme needs to make rooms feel open rather than crowded. Our small space and apartment decoration service focuses on exactly these challenges.</p><p>We begin with a measured plan, testing layouts so that living, dining, sleeping and working areas each have their place without blocking light or movement. Multi-functional pieces such as extending tables, storage beds, nesting tables and sofa beds help one room serve several purposes, and built-in storage keeps belongings out of sight.</p><p>Visual tricks then make the space feel larger: a light, cohesive palette, mirrors placed to reflect daylight, curtains hung high and wide, and furniture with raised legs that shows more floor. For specific rooms, our guides on <a href="/blog/how-to-arrange-furniture-in-a-small-living-room">arranging furniture in a small living room</a> and <a href="/blog/how-to-decorate-a-small-bedroom">decorating a small bedroom</a> share more of the thinking behind our approach.</p>',
            'included' => [
                ['Measured space planning', 'Scaled layouts that fit living, dining, sleeping and working zones into the space you have.'],
                ['Multi-functional furniture', 'Extending tables, storage beds, sofa beds and nesting pieces that do more than one job.'],
                ['Hidden & built-in storage', 'Under-bed drawers, wall cupboards and alcove joinery that keep surfaces clear.'],
                ['Light-enhancing palette', 'Colours, finishes and mirrors chosen to brighten rooms and open up sightlines.'],
                ['Zoning open-plan studios', 'Rugs, lighting and furniture placement that define areas without walls.'],
                ['Compact styling', 'A restrained, edited approach to accessories that keeps the home calm.'],
            ],
            'styles' => [
                ['Scandinavian Calm', 'Pale timber, white walls and soft textiles for a bright, airy feel.'],
                ['Warm Minimal', 'Neutral tones, low furniture and natural materials with very little clutter.'],
                ['Japandi', 'A blend of Japanese and Scandinavian simplicity with clean lines and natural textures.'],
                ['Compact Luxe', 'Glass, mirror, brass and velvet accents that bring polish to a small footprint.'],
            ],
            'benefits' => [
                ['Rooms that feel larger', 'Palette, mirrors and furniture scale work together to open up the space.'],
                ['More storage, less clutter', 'Built-in and hidden storage frees floors and surfaces.'],
                ['Flexible living', 'Multi-functional pieces let one room adapt to work, dining and guests.'],
                ['Confident buying', 'Every piece is checked against the plan before it is ordered.'],
            ],
            'materials' => [
                ['Light timbers & pale finishes', 'Oak, ash and soft neutrals that reflect light.'],
                ['Mirrors & glass', 'Reflective surfaces that increase the sense of space and light.'],
                ['Slim-profile furniture', 'Raised legs and lean frames that keep floors visible.'],
                ['Sheer & full-height curtains', 'Window dressings hung high and wide to make ceilings feel taller.'],
                ['Built-in joinery', 'Floor-to-ceiling storage that uses every centimetre.'],
            ],
            'meta_title' => 'Small Space & Apartment Decoration',
            'meta_description' => 'Small space and apartment decoration: smart layouts, multi-functional furniture, hidden storage and bright finishes that make compact homes feel bigger.',
            'sections' => [
                ['One room, many roles', '<p>In an apartment, the living room may also be the dining room, the office and the place guests sleep. We plan these roles together: a compact round table that seats two daily and more when extended, a corner sofa that defines the lounge, pendant lights that mark the dining spot and full-height curtains that make the room feel taller. Each zone is clear, yet the whole room still feels open.</p>', 'compact-apartment-living-dining'],
                ['Making corners count', '<p>Small homes often hide underused corners and alcoves. A pair of armchairs and a lamp can create a reading nook, a slim desk can fit beneath a window and floor-to-ceiling shelving can turn an awkward recess into valuable storage. Curtains and mirrors frame these spots so they feel intentional rather than squeezed in.</p>', 'reading-nook-curtains-mirror'],
            ],
            'gallery' => ['oak-vanity-round-mirror', 'kitchen-open-shelving-plants', 'living-room-shelves-paper-lanterns'],
            'related' => ['furniture-styling', 'home-office-decoration', 'curtains-window-treatments'],
            'projects' => [],
            'articles' => ['how-to-arrange-furniture-in-a-small-living-room', 'how-to-decorate-a-small-bedroom'],
            'faqs' => [
                ['How can I make a small apartment feel bigger?', 'A light, cohesive palette, mirrors that reflect daylight, furniture with raised legs, curtains hung high and wide, and good hidden storage all help. We combine these with a layout that keeps pathways and sightlines clear.'],
                ['Is multi-functional furniture worth it?', 'In compact homes it often is. Storage beds, extending tables, sofa beds and nesting tables let one room adapt to different needs. We recommend pieces that suit how you actually use your space.'],
                ['Can you decorate a studio apartment?', 'Yes. In studios we use rugs, lighting, shelving and furniture placement to define sleeping, living and working areas, so the single room feels organised and calm.'],
            ],
        ],

        'airbnb-vacation-rental-styling' => [
            'name' => 'Airbnb & Vacation Rental Styling',
            'icon' => 'suitcase',
            'short_description' => 'Guest-ready styling for short-term and holiday rentals, combining durable, easy-care furnishings with memorable details that photograph beautifully and make guests feel at home.',
            'hero_title' => 'Airbnb & Vacation Rental Styling Services',
            'hero_description' => 'We style holiday homes and short-term rentals to feel welcoming, look great in listing photos and stand up to a steady flow of guests.',
            'hero' => 'bedroom-lit-headboard',
            'intro' => '<p>A rental property has to work on several levels: it must catch attention in listing photos, feel comfortable and memorable during a stay, and remain easy to clean and maintain between guests. Our Airbnb and vacation rental styling service is designed around all three.</p><p>We start by understanding the property and who it is likely to welcome — couples on a weekend break, families on holiday or professionals on longer stays. That shapes the layout, the number of sleeping and seating spaces, and the amenities guests will look for, from a well-lit workspace to blackout curtains and plenty of storage for luggage.</p><p>Furniture and finishes are selected for durability and easy care, while a few distinctive details give the property a personality guests remember. We also prepare an inventory and styling notes so the look can be reset quickly after every stay. If you are also preparing a property to sell, see our <a href="/services/home-staging-property-sale">home staging</a> service.</p>',
            'included' => [
                ['Property & guest review', 'A plan based on the property, its likely guests and the experience you want to offer.'],
                ['Durable furnishing plan', 'Furniture, fabrics and finishes chosen to withstand frequent use and cleaning.'],
                ['Comfortable bedrooms', 'Quality bedding, blackout options, bedside lighting and luggage space.'],
                ['Photo-ready styling', 'Layouts, lighting and accessories arranged to look inviting in listing photos.'],
                ['Signature details', 'Distinctive art, textiles or a feature wall that make the property memorable.'],
                ['Inventory & reset guide', 'A list of items and simple styling notes so the look is easy to restore between stays.'],
            ],
            'styles' => [
                ['Boutique Hotel', 'Layered neutrals, a statement headboard and hotel-quality bedding.'],
                ['Coastal Escape', 'Light timbers, linen, natural fibres and soft blue or sand accents.'],
                ['Urban Apartment', 'Clean lines, a compact workspace and bold art for city stays.'],
                ['Family Holiday Home', 'Hard-wearing finishes, flexible sleeping and space for everyone.'],
            ],
            'benefits' => [
                ['Stand out in listings', 'A cohesive, well-styled property photographs better and attracts attention.'],
                ['Better guest experience', 'Comfort and thoughtful amenities help guests feel at home.'],
                ['Easier turnovers', 'Durable, easy-care materials and a reset guide save time between stays.'],
                ['A memorable identity', 'Signature details give the property a character guests talk about.'],
            ],
            'materials' => [
                ['Performance fabrics', 'Stain-resistant, washable upholstery and slipcovers.'],
                ['Hotel-quality bedding', 'Durable cotton sets and layered throws that wash well.'],
                ['Hard-wearing surfaces', 'Sealed timber, stone and laminate that stand up to frequent cleaning.'],
                ['Blackout curtains & blinds', 'Window treatments that help guests sleep well.'],
                ['Easy-care accessories', 'Art, ceramics and textiles chosen for impact and simple upkeep.'],
            ],
            'meta_title' => 'Airbnb & Vacation Rental Styling',
            'meta_description' => 'Airbnb and vacation rental styling: durable, easy-care furnishings, comfortable bedrooms and photo-ready details that make guests feel at home.',
            'sections' => [
                ['Bedrooms guests remember', '<p>The bedroom is where guests judge their stay. We focus on a comfortable bed dressed in quality layers, a headboard or panelled wall that gives the room presence, bedside lighting on both sides and blackout curtains for restful sleep. A chair, space for suitcases and hooks or a wardrobe for clothes make the room practical for stays of any length.</p>', 'guest-ready-grey-bedroom'],
                ['Spaces that photograph well', '<p>Most guests decide from photos, so we style each room with the camera in mind. Clear surfaces, balanced layouts, soft natural light and a few eye-catching details make rooms read clearly in pictures. The same choices help guests feel welcome the moment they arrive, because the property looks exactly as it was presented.</p>', 'backlit-round-mirror-bathroom'],
            ],
            'gallery' => ['bedroom-panelled-headboard-wall', 'warm-living-room-pampas-art', 'coffee-table-styling-tray'],
            'related' => ['home-staging-property-sale', 'bedroom-decoration', 'home-accessories-styling'],
            'projects' => [],
            'articles' => ['affordable-ways-to-refresh-your-home', 'home-decor-trends-worth-investing-in'],
            'faqs' => [
                ['Do you style properties that are already furnished?', 'Yes. We can work with existing furniture, recommending what to keep, what to replace and what to add so the property feels cohesive and guest-ready.'],
                ['How do you choose furniture for a rental?', 'We balance comfort and style with durability and easy cleaning, selecting performance fabrics, hard-wearing surfaces and pieces that can be replaced easily if needed.'],
                ['Can you provide a list of everything in the property?', 'We prepare an inventory and simple styling notes so cleaners or hosts can reset each room quickly between stays.'],
            ],
        ],

        'home-staging-property-sale' => [
            'name' => 'Home Staging for Property Sale',
            'icon' => 'tag',
            'short_description' => 'Professional home staging that presents your property at its best for sale, with decluttering guidance, furniture arrangement and neutral styling that helps buyers picture themselves living there.',
            'hero_title' => 'Home Staging Services for Property Sale',
            'hero_description' => 'We prepare homes for viewings and listing photos, highlighting each room\'s strengths so buyers can see the space, the light and the life the property offers.',
            'hero' => 'bright-living-room-rattan-sofa',
            'intro' => '<p>When a home goes on the market, buyers form an impression within moments, often from the first listing photo. Home staging helps that impression count. Our home staging service presents your property so its space, light and best features are easy to see and easy to imagine living with.</p><p>The process usually begins with a walk-through and clear, practical advice on decluttering, depersonalising and small repairs. We then plan each room: arranging or supplementing furniture to show how spaces can be used, defining any awkward or empty areas and making sure circulation feels generous.</p><p>Styling is kept fresh and broadly appealing — light, neutral bedding, a few plants and flowers, carefully chosen art and soft textiles — so rooms feel warm without being personal. Staging can be applied to a lived-in home or an empty one, and the same principles help when <a href="/services/airbnb-vacation-rental-styling">styling a property for short-term rental</a>.</p>',
            'included' => [
                ['Walk-through & action list', 'A room-by-room list of decluttering, repair and presentation priorities.'],
                ['Furniture arrangement', 'Layouts that show the size and purpose of each room and keep pathways clear.'],
                ['Neutral styling scheme', 'Bedding, cushions, rugs and art with broad appeal and a fresh, clean feel.'],
                ['Defining key spaces', 'Showing how spare rooms, nooks and open areas can be used.'],
                ['Photo-day preparation', 'Final styling, lighting and detailing before listing photography.'],
                ['Viewing-ready guidance', 'Simple tips to keep the home looking its best throughout viewings.'],
            ],
            'styles' => [
                ['Light & Neutral', 'Soft whites, warm greys and natural textures with wide appeal.'],
                ['Warm Contemporary', 'Timber, linen and greenery that make rooms feel inviting.'],
                ['Classic Refined', 'Balanced, symmetrical arrangements that highlight architectural features.'],
                ['Vacant Property Staging', 'A complete furnished look for empty homes so buyers can gauge scale.'],
            ],
            'benefits' => [
                ['Stronger first impressions', 'A well-presented home stands out in listings and on viewing day.'],
                ['Space made visible', 'Arrangement and styling help buyers understand each room\'s size and use.'],
                ['Easier to imagine living there', 'Neutral, warm styling lets buyers picture their own life in the home.'],
                ['Better listing photos', 'Clean, balanced rooms photograph clearly and attractively.'],
            ],
            'materials' => [
                ['Crisp neutral bedding', 'Fresh, layered bedding that makes bedrooms look restful and spacious.'],
                ['Natural textiles', 'Linen, cotton and wool cushions, throws and rugs in calm tones.'],
                ['Plants & fresh flowers', 'Greenery and flowers that bring life and colour to every room.'],
                ['Artwork & mirrors', 'Pieces that add interest and reflect light without overpowering.'],
            ],
            'meta_title' => 'Home Staging for Property Sale',
            'meta_description' => 'Home staging for property sale: decluttering guidance, furniture arrangement and neutral styling that present your home at its best for photos and viewings.',
            'sections' => [
                ['First impressions in every room', '<p>Buyers walk through a home quickly, so each room needs a clear purpose and an inviting focal point. We arrange furniture to show off the room\'s size and its best features — a fireplace, a view, generous windows — and edit away anything that distracts. Soft daylight, open curtains and a few fresh flowers then make the space feel bright and cared for.</p>', 'open-plan-minimal-living-room'],
                ['Bedrooms that feel like a retreat', '<p>Bedrooms are often where buyers imagine their future most clearly. Crisp, layered bedding, matching bedside lamps, a bench or armchair and clear surfaces suggest calm and space. Personal items are kept to a minimum so the room feels like a blank canvas waiting for its next owners.</p>', 'symmetrical-panelled-bedroom'],
            ],
            'gallery' => ['warm-minimal-living-room-sunset', 'white-handleless-kitchen', 'minimal-living-room-fireplace'],
            'related' => ['airbnb-vacation-rental-styling', 'furniture-styling', 'home-accessories-styling'],
            'projects' => [],
            'articles' => ['affordable-ways-to-refresh-your-home', 'how-to-arrange-furniture-in-a-small-living-room'],
            'faqs' => [
                ['Can you stage a home that we are still living in?', 'Yes. Staging a lived-in home usually focuses on decluttering, rearranging existing furniture and adding a few fresh accessories, with simple guidance to keep things viewing-ready.'],
                ['Do you stage empty properties?', 'We can stage vacant properties with furniture and accessories so buyers can understand the scale and purpose of each room. We will discuss the scope during the consultation.'],
                ['When should staging be done?', 'Ideally before listing photos are taken, so the property is presented at its best from the very first impression online.'],
            ],
        ],

        'seasonal-holiday-home-decoration' => [
            'name' => 'Seasonal & Holiday Home Decoration',
            'icon' => 'gift',
            'short_description' => 'Festive and seasonal styling that brings warmth and celebration into your home, from Eid, Christmas and New Year to weddings, family gatherings and simple changes with the seasons.',
            'hero_title' => 'Seasonal & Holiday Home Decoration Services',
            'hero_description' => 'We dress your home for the moments that matter — festive celebrations, family occasions and the changing seasons — with styling that feels elegant, personal and joyful.',
            'hero' => 'christmas-living-room-fireplace',
            'intro' => '<p>Celebrations feel more special when the home is ready for them. Our seasonal and holiday home decoration service brings festive atmosphere into your rooms with the same eye for balance and quality that we bring to any design project — so the result feels elegant rather than overdone.</p><p>We plan each occasion around your home and traditions: an entrance that welcomes guests, a dining table dressed for a celebratory meal, a living room focal point such as a mantel, tree or feature display, and lighting that adds warmth and sparkle. Eid, Christmas, New Year, weddings, birthdays and family gatherings each have their own character, and we tailor the colours, materials and details accordingly.</p><p>Beyond festivals, small seasonal changes keep a home feeling fresh — lighter linens and greenery for warmer months, richer textures and candlelight when the weather cools. Our journal article on <a href="/blog/seasonal-decor-ideas-through-the-year">seasonal decor through the year</a> shares simple ideas, and our <a href="/services/home-accessories-styling">home accessories and styling</a> service can make those changes for you.</p>',
            'included' => [
                ['Occasion planning', 'A decoration plan built around your celebration, traditions and guest list.'],
                ['Festive focal points', 'Mantels, trees, feature walls and entrance displays that set the scene.'],
                ['Table styling', 'Tablescapes, centrepieces and place settings for celebratory meals.'],
                ['Decorative lighting', 'Fairy lights, lanterns and candles that add warmth and sparkle.'],
                ['Seasonal textiles & accents', 'Cushions, throws, runners and botanicals that refresh rooms for the season.'],
                ['Set-up & take-down', 'Installation before the occasion and careful packing away afterwards.'],
            ],
            'styles' => [
                ['Classic Festive', 'Greenery, warm lights, gold accents and traditional seasonal colours.'],
                ['Elegant Neutral', 'Ivory, champagne and natural textures for a refined celebration.'],
                ['Eid & Celebration Glow', 'Lanterns, warm lighting, rich textiles and beautifully dressed tables.'],
                ['Seasonal Refresh', 'Gentle updates to linens, greenery and accents as the seasons change.'],
            ],
            'benefits' => [
                ['A home ready to celebrate', 'Your rooms feel festive and welcoming when guests arrive.'],
                ['Elegant, never cluttered', 'Decorations are balanced with your existing interior for a polished look.'],
                ['Time to enjoy the occasion', 'We handle planning, set-up and take-down so you can focus on family.'],
                ['Personal to your traditions', 'Each scheme reflects the celebrations and customs that matter to you.'],
            ],
            'materials' => [
                ['Fresh & faux greenery', 'Garlands, wreaths and arrangements that bring natural texture.'],
                ['Warm decorative lighting', 'Fairy lights, lanterns and candles for a soft festive glow.'],
                ['Rich seasonal textiles', 'Velvet, wool and linen cushions, throws and runners.'],
                ['Metallic & glass accents', 'Gold, brass and glass ornaments that catch the light.'],
                ['Table linens & tableware', 'Runners, napkins and centrepieces for celebratory dining.'],
            ],
            'meta_title' => 'Seasonal & Holiday Home Decoration',
            'meta_description' => 'Seasonal and holiday home decoration for Eid, Christmas, New Year and family celebrations: festive focal points, table styling, lighting and seasonal refreshes.',
            'sections' => [
                ['Celebrations, beautifully styled', '<p>Festive decorating works best with a clear focal point and a restrained palette. A garland-draped mantel, a lit tree or a lantern display becomes the heart of the room, supported by warm lighting and a few repeated colours and materials elsewhere. Keeping decorations in harmony with your existing interior makes the home feel festive and elegant at the same time.</p>', null],
                ['Refreshing your home with the seasons', '<p>Seasonal styling does not have to mean a full redecoration. Swapping cushion covers and throws, changing the botanicals on a coffee table or mantel, and adjusting lighting from bright and airy to warm and candlelit can make familiar rooms feel new. We plan these changes so they are simple to store and repeat each year.</p>', 'warm-living-room-pampas-art'],
            ],
            'gallery' => ['christmas-living-room-fireplace', 'styled-cosy-living-room', 'classic-dining-room-drapes'],
            'related' => ['home-accessories-styling', 'dining-room-decoration', 'living-room-decoration'],
            'projects' => [],
            'articles' => ['seasonal-decor-ideas-through-the-year', 'affordable-ways-to-refresh-your-home'],
            'faqs' => [
                ['Which occasions do you decorate for?', 'We style homes for festivals such as Eid, Christmas and New Year, as well as weddings, birthdays, family gatherings and seasonal refreshes. Every scheme is planned around your traditions and home.'],
                ['Do you set up and remove the decorations?', 'Set-up before the occasion and careful take-down afterwards can be included, so you can enjoy the celebration without the extra work.'],
                ['Can you use decorations I already own?', 'Yes. We are happy to incorporate your existing decorations and family pieces, adding new items only where they complete the look.'],
            ],
        ],
    ];

    public function run(): void
    {
        $order = self::FIRST_SORT_ORDER - 1;

        foreach (self::SERVICES as $slug => $data) {
            $order++;

            // Never overwrite a service that already exists — it may have been edited in the admin.
            if (Service::withTrashed()->where('slug', $slug)->exists()) {
                continue;
            }

            DB::transaction(function () use ($slug, $data, $order) {
                $service = $this->upsertService($slug, $data, $order);
                $this->syncSections($service, $data['sections']);
                $this->syncGallery($service, $data['gallery']);
                $service->projects()->sync(Project::whereIn('slug', $data['projects'])->pluck('id'));
                $service->articles()->sync($this->ordered(BlogPost::class, $data['articles']));

                foreach ($data['faqs'] as $i => [$question, $answer]) {
                    Faq::create([
                        'question' => $question, 'answer' => $answer, 'service_id' => $service->id,
                        'category' => 'services', 'show_on_home' => false, 'is_published' => true, 'sort_order' => $i + 1,
                    ]);
                }
            });
        }

        // Related services last, so links between two new services resolve. Only fills empty lists.
        $ids = Service::pluck('id', 'slug');
        foreach (self::SERVICES as $slug => $data) {
            $service = Service::where('slug', $slug)->first();
            if ($service && $service->related()->doesntExist()) {
                $service->related()->sync(collect($data['related'])->map(fn ($s) => $ids[$s] ?? null)->filter()->values());
            }
        }
    }

    /** Ids keyed for a pivot sync with sort_order, in the given slug order; unknown slugs are skipped. */
    private function ordered(string $model, array $slugs): array
    {
        $ids = $model::whereIn('slug', $slugs)->pluck('id', 'slug');

        return collect($slugs)->filter(fn ($s) => isset($ids[$s]))->values()
            ->mapWithKeys(fn ($s, $i) => [$ids[$s] => ['sort_order' => $i + 1]])->all();
    }
}
