# Architecture

Laravel 12 · Blade components · Tailwind CSS v4 · Alpine.js · MySQL.

## Principles

- **Pages are data.** Every public page is a `pages` row with ordered `page_sections`. Each section `type`
  maps 1:1 to a Blade component in `resources/views/components/sections/{type}.blade.php` and is rendered by
  `<x-sections.render :page="$page" />`. Admins reorder, enable/disable and edit sections without code.
- **One template per content type.** `services/show.blade.php` renders all 10 services, `projects/show.blade.php`
  every portfolio project, `blog/show.blade.php` every article.
- **Small, React-style components.** UI lives in `resources/views/components` (`<x-button>`, `<x-picture>`,
  `<x-section-heading>`, `<x-card.service>` …). Pages compose components; components take props and slots.
- **No fabricated facts.** Stats, testimonials, team, before/after and service-area pages render only when real
  data exists. Empty business settings are hidden on the site and flagged in the admin dashboard.

## Images

Originals stay in `/Image` (outside `public/`). `php artisan images:import` reads `database/data/image-manifest.php`
(visual inventory: slug, category, alt text) and writes WebP + JPEG variants at 480/960/1600/2400 px (never upscaled)
to `public/media/library/{slug}-{width}.{webp|jpg}`. Admin uploads use the same `App\Services\ImageOptimizer`
and go to `public/media/uploads/YYYY/MM/`. Render images with `<x-picture :media="$media" sizes="…" />`.

In seeders, look media up by slug: `Media::where('path', 'media/library/{slug}')->value('id')`
(helper: `Database\Seeders\Concerns\SeedsMedia::media('slug')`).

## Section types and their `data` keys

All sections have: `eyebrow`, `heading`, `body` (sanitised HTML or plain text), `media_id`, `data` (JSON), `is_enabled`, `sort_order`.

| type | uses | `data` keys |
|---|---|---|
| `announcement` | body | `link_label`, `link_url` (phone/email/area from settings are appended automatically) |
| `hero` | eyebrow, heading, body, media | `buttons: [{label, url, style: primary\|outline\|light}]`, `size: full\|page` |
| `stats` | heading | `items: [{value, label}]` — **verified figures only**; section hidden when empty |
| `text_image` | eyebrow, heading, body, media | `image_position: left\|right`, `bullets: [string]`, `button: {label, url}` |
| `services` | eyebrow, heading, body | `limit` (int), `featured_only` (bool) |
| `features` | eyebrow, heading, body | `items: [{icon, title, text}]`, `columns: 2\|3\|4` |
| `projects` | eyebrow, heading, body | `limit`, `featured_only` |
| `before_after` | eyebrow, heading, body | — shows projects that have both before & after images; hidden otherwise |
| `process` | eyebrow, heading, body | `layout: grid\|timeline` — steps come from setting `process_steps` |
| `styles` | eyebrow, heading, body | `items: [{title, text, media_id}]` |
| `gallery` | eyebrow, heading, body | `category` (media category), `limit`, `media_ids: [int]` |
| `portfolio` | eyebrow, heading, body | filterable project grid with lightbox; `page_size` |
| `testimonials` | eyebrow, heading, body | `limit` (omit for all) — approved testimonials only; hidden when none |
| `blog` | eyebrow, heading, body | `limit` |
| `faqs` | eyebrow, heading, body | `scope: home\|all` (`all` groups by category) |
| `team` | eyebrow, heading, body | `items: [{name, role, bio, media_id}]` — hidden when empty |
| `contact` | eyebrow, heading, body | contact details from settings + contact form |
| `consultation_form` | eyebrow, heading, body | full consultation request form |
| `service_areas` | eyebrow, heading, body | lists published service areas |
| `cta` | eyebrow, heading, body, media | `buttons: [{label, url, style}]` |

Icons available to `<x-icon name="…">`: see `resources/views/components/icon.blade.php`.

## Settings (`settings` table, `App\Models\Setting::get('key')`)

`site_name`, `tagline`, `company_description`, `phone`, `whatsapp`, `email`, `address`, `city`, `country`,
`business_hours`, `map_embed_url`, `service_area_text`, `social_instagram`, `social_facebook`, `social_pinterest`,
`social_youtube`, `social_tiktok`, `notification_email`, `process_steps` ([{title, text, icon, media_id}]),
`seo_title_suffix`, `seo_default_description`, `seo_default_og_media_id`, `google_site_verification`,
`ga_measurement_id`, `local_business_enabled`, `whatsapp_float_enabled`, `footer_note`.

## Routes

Public: `/`, `/services`, `/services/{slug}`, `/gallery`, `/gallery/{slug}`, `/blog`, `/blog/category/{slug}`,
`/blog/{slug}`, `/about-us`, `/our-process`, `/testimonials`, `/faqs`, `/book-consultation`, `/contact`,
`/service-areas`, `/service-areas/{slug}`, `/privacy-policy`, `/terms-conditions`, `/cookie-policy`,
`/sitemap.xml`, `/robots.txt`. Admin: `/admin/*` (see `routes/admin.php`).
