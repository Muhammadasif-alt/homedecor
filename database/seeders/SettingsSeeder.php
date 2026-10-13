<?php

namespace Database\Seeders;

use App\Models\Setting;
use Database\Seeders\Concerns\SeedsMedia;
use Illuminate\Database\Seeder;

/**
 * Site-wide settings (see docs/ARCHITECTURE.md → Settings).
 *
 * Contact details, address and social links are deliberately left empty:
 * they must be supplied by the business in the admin panel, never invented.
 */
class SettingsSeeder extends Seeder
{
    use SeedsMedia;

    private const SETTINGS = [
        'site_name' => 'Home Decor Studio', // draft name — to be confirmed by the business
        'tagline' => 'Thoughtful home decor, designed around the way you live',
        'company_description' => 'Home Decor Studio creates personalised decoration schemes for living rooms, bedrooms, kitchens and complete homes. Every project begins with listening, then brings together colour, texture, furniture and light into spaces that feel calm, considered and entirely your own.',

        // Business facts — left empty until the business provides them.
        'phone' => '',
        'whatsapp' => '',
        'email' => '',
        'address' => '',
        'city' => '',
        'country' => '',
        'business_hours' => '',
        'map_embed_url' => '',
        'service_area_text' => '',
        'social_instagram' => '',
        'social_facebook' => '',
        'social_pinterest' => '',
        'social_youtube' => '',
        'social_tiktok' => '',
        'notification_email' => '',

        // SEO & integrations
        'seo_title_suffix' => ' | Home Decor Studio',
        'seo_default_description' => 'Personalised home decoration services for living rooms, bedrooms, kitchens and whole homes. Book a consultation to begin shaping a space that feels like you.',
        'google_site_verification' => '',
        'ga_measurement_id' => '',
        'local_business_enabled' => false,
        'whatsapp_float_enabled' => true,
        'footer_note' => 'Personalised decoration and styling for every room in your home.',
    ];

    /** [title, text, icon, media slug|null] */
    private const PROCESS_STEPS = [
        ['Initial Consultation', 'We begin with a relaxed conversation about your home, how you use each space and the feeling you would like it to have. This is where we understand your priorities, preferences and budget before any design work starts.', 'message-circle', 'site-plan-review-desk'],
        ['Space Assessment', 'We review the rooms in detail, noting proportions, natural light, existing furniture and architectural features. Careful measurements and observations give the design a reliable foundation.', 'ruler', 'site-walkthrough-client'],
        ['Design Concept', 'We develop a concept that brings together palette, materials, furniture layout and lighting into one coherent direction. You will see how the ideas fit your space and have the opportunity to refine them with us.', 'palette', 'floor-plan-sketching'],
        ['Proposal & Approval', 'You receive a clear proposal setting out the agreed scope, selected items and costs. Nothing proceeds until you are comfortable with every detail and have given your approval.', 'file-text', 'proposal-cost-estimate-laptop'],
        ['Decor Execution', 'We coordinate the ordering, delivery and installation of every element, from wall finishes and curtains to furniture and lighting. Progress is shared with you along the way so there are no surprises.', 'hammer', 'renovation-half-finished-living-room'],
        ['Final Styling & Handover', 'We finish each room with artwork, textiles and accessories, adjusting the details until everything feels balanced. We then walk you through the completed space and share guidance on caring for it.', 'sparkles', 'styled-cosy-living-room'],
    ];

    public function run(): void
    {
        Setting::put(self::SETTINGS + [
            'process_steps' => $this->processSteps(),
            'seo_default_og_media_id' => $this->media('double-height-ring-chandelier-fireplace'),
        ]);
    }

    private function processSteps(): array
    {
        return array_map(fn (array $step) => [
            'title' => $step[0],
            'text' => $step[1],
            'icon' => $step[2],
            'media_id' => $step[3] ? $this->media($step[3]) : null,
        ], self::PROCESS_STEPS);
    }
}
