<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * General and service-specific FAQs. Answers describe how the process works;
 * they never state prices, guarantees or policies the business has not confirmed.
 */
class FaqSeeder extends Seeder
{
    /** [category, question, answer, show_on_home] */
    private const GENERAL = [
        ['general', 'What kinds of homes do you work on?', 'We work on houses, apartments and villas, from single rooms to complete homes. Whether you are moving into a new property, renovating or simply refreshing a space that no longer feels right, we tailor the scope of work to your home and your goals.', true],
        ['services', 'Can you work with furniture and pieces I already own?', 'Absolutely. We always review what you already have and look for ways to give favourite pieces a natural place in the new scheme — whether that means repositioning, reupholstering or restyling them. New items are introduced only where they genuinely add something.', true],
        ['pricing', 'How much does a decoration project cost?', 'Every home and brief is different, so every quote is tailored after the consultation. Once we understand the rooms involved, the scope of work and your preferences, we prepare a clear proposal setting out the agreed scope and costs before anything proceeds.', true],
        ['pricing', 'Can you work within a set budget?', 'Yes. Sharing your budget early helps us recommend where to invest for the greatest impact and where more economical choices will work just as well. We then shape the proposal around the figure you are comfortable with.', false],
        ['timeline', 'How long does a typical project take?', 'Timelines depend on the size of the project, the finishes chosen and the lead times of furniture and materials. We outline an expected schedule in your proposal and keep you updated on progress as work moves from concept to installation.', true],
        ['timeline', 'Can the work be done in phases?', 'Yes. Many clients prefer to decorate room by room or floor by floor. We can create an overall plan for the whole home and then deliver it in stages that suit your timeline and priorities.', false],
        ['materials', 'Will I be able to see materials and samples before deciding?', 'Yes. As part of the design concept stage we share proposed colours, fabrics and finishes so you can see how they look together and in your own light before any final decisions are made.', false],
        ['materials', 'Can furniture, curtains and finishes be customised?', 'Many elements can be tailored, including made-to-measure curtains and blinds, upholstery fabrics, panelling and joinery finishes. We will explain the options available for each part of your project during the design stage.', false],
        ['booking', 'What happens during the initial consultation?', 'The consultation is a conversation about your home, how you use each space, the style you are drawn to and your priorities and budget. It helps us understand your goals and recommend the most suitable next steps.', true],
        ['booking', 'How do I book a consultation?', 'You can request a consultation through the booking form on our website or by getting in touch via the contact page. Share a few details about your home and project, and we will be in touch to arrange a suitable time.', false],
    ];

    /** service slug => [[question, answer]] */
    private const BY_SERVICE = [
        'living-room-decoration' => [
            ['Can you decorate an open-plan living area?', 'Yes. In open-plan homes we use furniture placement, rugs and lighting to define the living zone while keeping a sense of connection with the kitchen and dining areas, so the whole space feels coherent.'],
            ['Do you plan around a television or fireplace?', 'We plan the layout around whichever focal point matters most to you — a television, a fireplace, a view or a combination — so seating, lighting and storage all support the way you use the room.'],
            ['Can you refresh my living room without replacing everything?', 'Certainly. A refresh might focus on new soft furnishings, lighting, artwork or a rearrangement of existing furniture. We will recommend the changes that make the biggest difference for your room.'],
        ],
        'bedroom-decoration' => [
            ['Do you decorate guest rooms and children\'s rooms as well?', 'Yes. Every bedroom is designed for the person who will use it, whether that is a principal suite, a welcoming guest room or a child\'s room that can adapt as they grow.'],
            ['Can you design a feature headboard wall?', 'We regularly design upholstered, panelled or timber headboard walls, sometimes with integrated lighting. We will suggest options that suit your bedroom\'s proportions and style.'],
            ['How do you approach lighting in a bedroom?', 'We combine soft ambient lighting, reading lights and gentle accent lighting, ideally on dimmers, together with window treatments that balance daylight and darkness for restful sleep.'],
        ],
        'kitchen-decoration' => [
            ['Do I need a new kitchen to change how it looks?', 'Not necessarily. Decorative updates such as cabinet colours, hardware, splashback finishes, lighting and seating can significantly change the character of a kitchen. We will advise on what suits your existing layout.'],
            ['Can you help choose island pendants?', 'Yes. We help select pendant styles and sizes and advise on spacing and hanging heights relative to your island and ceiling, so they feel well proportioned and provide useful light.'],
            ['Can you style open shelving in the kitchen?', 'We style open shelves with a balance of everyday essentials and decorative pieces, keeping arrangements practical and easy to maintain.'],
        ],
        'bathroom-decoration' => [
            ['Can a small bathroom feel more spacious?', 'Yes. Mirrors, wall-hung vanities, light finishes and well-placed lighting can all help a compact bathroom feel larger and brighter. We tailor the approach to your room.'],
            ['What finishes do you recommend for bathrooms?', 'We suggest finishes suited to humid environments, such as sealed timber, stone and porcelain surfaces, chosen to balance beauty with easy everyday upkeep.'],
            ['Do you advise on bathroom lighting?', 'We plan mirror lighting for grooming alongside softer ambient and niche lighting, so the bathroom works well in the morning and feels relaxing in the evening.'],
        ],
        'wall-decor-wallpaper' => [
            ['Which rooms suit a feature wall?', 'Feature walls work well behind a bed, around a television or fireplace, in a dining room or along an entrance hall. We will recommend where a feature will have the most impact in your home.'],
            ['Can panelling include lighting?', 'Yes. Timber, fluted and three-dimensional panels can incorporate concealed LED lighting for a soft evening glow. We coordinate the detailing with installers.'],
            ['Do you help with hanging art and mirrors?', 'We advise on the scale, framing and hanging height of artwork and mirrors, and can plan gallery walls so the arrangement feels balanced.'],
        ],
        'curtains-window-treatments' => [
            ['Are your curtains made to measure?', 'We design curtains and blinds to the exact measurements of your windows, which is especially helpful for tall, wide or unusually shaped openings.'],
            ['Can I combine sheers with blackout curtains?', 'Yes. Layering sheers with lined or blackout curtains is a popular choice for bedrooms and living rooms, giving you softness by day and privacy or darkness at night.'],
            ['Do you offer motorised tracks?', 'Motorised tracks and blinds are one of the options we can include, particularly for large or hard-to-reach windows. We will discuss whether they suit your project.'],
        ],
        'furniture-styling' => [
            ['Can you rearrange my existing furniture?', 'Yes. Sometimes the most effective change is a new arrangement of pieces you already own. We assess the room and suggest layouts that improve flow, comfort and balance.'],
            ['Do you help source new furniture?', 'We help select new pieces that suit your space, lifestyle and scheme, checking scale and fit against a measured plan before anything is ordered.'],
            ['Can you plan a home office or dressing room?', 'Yes. We plan desks, shelving, storage, seating and lighting for studies and dressing rooms, focusing on practicality as much as appearance.'],
        ],
        'lighting-ceiling-decor' => [
            ['What is layered lighting?', 'Layered lighting combines ambient light for overall brightness, task light for activities such as reading and cooking, and accent light to highlight features. Together they let a room adapt to any time of day.'],
            ['How do you choose the right chandelier size?', 'We consider the room\'s dimensions, ceiling height and the furniture beneath, such as a dining table, to select a fitting that feels proportionate from every viewpoint.'],
            ['Can you add cove or recessed ceiling lighting?', 'Ceiling coves, recesses and concealed LED strips are among the options we design. We coordinate the detailing with the relevant trades during installation.'],
        ],
        'home-accessories-styling' => [
            ['Can styling be done without other decoration work?', 'Yes. Styling is a standalone service and a great way to refresh rooms with artwork, accessories, plants and textiles without any building work.'],
            ['Will you use my existing accessories?', 'We always start with what you own, rediscovering favourite pieces and giving them a better setting before suggesting any additional items.'],
            ['Can you refresh my home for different seasons?', 'We can suggest seasonal changes to cushions, throws, botanicals and decorative objects so your home feels fresh throughout the year.'],
        ],
        'full-home-decoration' => [
            ['How do you keep a whole home cohesive?', 'We create a core palette of materials, colours and styles, then interpret it in each room with varying intensity, so every space has its own mood while the home feels connected.'],
            ['Can a full home project be delivered in stages?', 'Yes. We can plan the whole home at the outset and then deliver it room by room or floor by floor, according to your timeline and priorities.'],
            ['Who coordinates suppliers and installers?', 'We coordinate the ordering, deliveries and specialist installers involved in your project, giving you a single point of contact throughout.'],
        ],
    ];

    public function run(): void
    {
        foreach (self::GENERAL as $i => [$category, $question, $answer, $onHome]) {
            $this->upsert($question, $answer, $category, null, $onHome, $i + 1);
        }

        $services = Service::whereIn('slug', array_keys(self::BY_SERVICE))->pluck('id', 'slug');

        foreach (self::BY_SERVICE as $slug => $faqs) {
            foreach ($faqs as $i => [$question, $answer]) {
                $this->upsert($question, $answer, 'services', $services[$slug], false, $i + 1);
            }
        }
    }

    private function upsert(string $question, string $answer, string $category, ?int $serviceId, bool $onHome, int $order): void
    {
        Faq::updateOrCreate(['question' => $question, 'service_id' => $serviceId], [
            'answer' => $answer,
            'category' => $category,
            'show_on_home' => $onHome,
            'is_published' => true,
            'sort_order' => $order,
        ]);
    }
}
