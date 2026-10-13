# Home Decor Studio — Website & CMS

A premium home decor services website with a full admin CMS, built with **Laravel 12, Blade components,
Tailwind CSS v4, Alpine.js and MySQL**, ready for Hostinger shared hosting.

## Features

- **24 core pages**: Home (16 CMS sections), Services overview, 10 service pages rendered by **one shared template**
  (`resources/views/services/show.blade.php`), Gallery/Portfolio with filters + lightbox, project detail template,
  Blog with categories, search, table of contents and article template, About, Process, Testimonials, FAQs,
  Book Consultation, Contact, Service Areas, Privacy / Terms / Cookie policies.
- **Admin CMS** at `/admin`: dashboard + launch checklist, pages & reorderable sections, services, portfolio,
  media library (upload/replace/categorise), blog & categories, testimonials, FAQs, leads with status pipeline and
  notes, navigation, settings, service areas, 301 redirects, users & roles.
- **Real images**: 122 originals in `/Image` (JPG + JFIF) → responsive WebP/JPEG variants via `php artisan images:import`.
- **SEO**: unique meta per page, canonical, Open Graph/Twitter, JSON-LD (Organization/LocalBusiness, Service,
  BreadcrumbList, BlogPosting, FAQPage), XML sitemap, robots.txt, redirects, noindex controls, custom 404.
- **Security**: CSRF, rate-limited forms and login, honeypot + timing spam check, policies/roles, purified rich text,
  MIME-verified uploads (attachments stored privately), security headers, HTTPS enforcement in production.

## Local setup

```bash
composer install
npm install && npm run build
cp .env.example .env        # set APP_ENV=local, APP_DEBUG=true, APP_URL, DB_* (MySQL)
php artisan key:generate
php artisan migrate
php -d memory_limit=1G artisan images:import   # converts /Image → public/media/library
php artisan db:seed                              # set ADMIN_EMAIL / ADMIN_PASSWORD in .env first
php artisan serve
```

Tests (SQLite in-memory, never touch your MySQL data): `php artisan test`

## Project structure

| Path | Purpose |
|---|---|
| `app/Http/Controllers` | Public controllers (thin) — `Admin/` for the CMS |
| `app/View/SectionData.php` | Loads data for dynamic page sections |
| `app/Services/ImageOptimizer.php` | JPG/PNG/WebP/JFIF → WebP + JPEG responsive variants |
| `app/Support/Seo.php`, `SchemaFactory.php` | Meta tags, breadcrumbs and JSON-LD |
| `resources/views/components` | Reusable UI components (`sections/*` = CMS blocks, `card/*`, `form/*`, `site/*`, `admin/*`) |
| `database/data/image-manifest.php` | Visual inventory of every original photo (slug, category, alt text) |
| `docs/ARCHITECTURE.md` | Section types, settings keys, conventions |
| `docs/DEPLOYMENT.md` | Hostinger deployment, backups and restore |

## Content status

All copy is original. **No testimonials, statistics, team members, locations or contact details were invented** —
those sections stay hidden until real data is added in the admin. Items to confirm before launch are listed on the
admin dashboard's *Launch checklist*.
