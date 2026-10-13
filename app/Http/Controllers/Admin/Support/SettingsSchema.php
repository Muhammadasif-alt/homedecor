<?php

namespace App\Http\Controllers\Admin\Support;

/**
 * Grouped definition of the site settings form (keys documented in docs/ARCHITECTURE.md).
 * Types: text, email, tel, url, textarea, bool, media.
 */
class SettingsSchema
{
    public const PROCESS_FIELDS = ['title', 'text', 'icon', 'media_id'];

    public static function groups(): array
    {
        return [
            'Business details' => [
                'site_name' => ['label' => 'Business name', 'required' => true],
                'tagline' => ['label' => 'Tagline'],
                'company_description' => ['label' => 'Company description', 'type' => 'textarea', 'hint' => 'A short paragraph used in the footer and structured data.'],
                'service_area_text' => ['label' => 'Service area summary', 'type' => 'textarea', 'hint' => 'e.g. “Serving Dubai Marina, JBR and Palm Jumeirah.” Only list areas you really serve.'],
            ],
            'Contact & hours' => [
                'phone' => ['label' => 'Phone', 'type' => 'tel'],
                'whatsapp' => ['label' => 'WhatsApp number', 'type' => 'tel', 'hint' => 'International format, e.g. +971501234567.'],
                'email' => ['label' => 'Public email', 'type' => 'email'],
                'notification_email' => ['label' => 'Lead notification email', 'type' => 'email', 'hint' => 'New enquiries are sent here (not shown publicly).'],
                'address' => ['label' => 'Street address'],
                'city' => ['label' => 'City'],
                'country' => ['label' => 'Country'],
                'business_hours' => ['label' => 'Business hours', 'type' => 'textarea', 'hint' => 'One line per day range, e.g. “Mon–Fri: 9:00–18:00”.'],
                'map_embed_url' => ['label' => 'Google Maps embed URL', 'type' => 'url', 'hint' => 'From Google Maps → Share → Embed a map (the src link only).'],
            ],
            'Social media' => [
                'social_instagram' => ['label' => 'Instagram URL', 'type' => 'url'],
                'social_facebook' => ['label' => 'Facebook URL', 'type' => 'url'],
                'social_pinterest' => ['label' => 'Pinterest URL', 'type' => 'url'],
                'social_youtube' => ['label' => 'YouTube URL', 'type' => 'url'],
                'social_tiktok' => ['label' => 'TikTok URL', 'type' => 'url'],
            ],
            'SEO & analytics' => [
                'seo_title_suffix' => ['label' => 'Title suffix', 'hint' => 'Appended to page titles, e.g. “ | Studio Name”.'],
                'seo_default_description' => ['label' => 'Default meta description', 'type' => 'textarea', 'counter' => 155],
                'seo_default_og_media_id' => ['label' => 'Default social share image', 'type' => 'media'],
                'google_site_verification' => ['label' => 'Google Search Console verification code'],
                'ga_measurement_id' => ['label' => 'Google Analytics 4 measurement ID', 'hint' => 'Format G-XXXXXXX. Leave empty to disable analytics.'],
                'local_business_enabled' => ['label' => 'Output LocalBusiness structured data', 'type' => 'bool', 'hint' => 'Enable once address, phone and hours are complete and accurate.'],
            ],
            'Footer & extras' => [
                'footer_note' => ['label' => 'Footer note', 'type' => 'textarea'],
                'whatsapp_float_enabled' => ['label' => 'Show floating WhatsApp button', 'type' => 'bool'],
            ],
        ];
    }

    /** Flat key => field config. */
    public static function fields(): array
    {
        return array_merge(...array_values(self::groups()));
    }
}
