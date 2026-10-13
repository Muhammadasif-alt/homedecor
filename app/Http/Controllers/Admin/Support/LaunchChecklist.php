<?php

namespace App\Http\Controllers\Admin\Support;

use App\Models\Page;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;

/**
 * Pre-launch checks shown on the dashboard: highlights missing business details and
 * placeholder content so nothing fabricated or empty goes live.
 */
class LaunchChecklist
{
    public const DEFAULT_SITE_NAME = 'Home Decor Studio';

    /** @return list<array{label:string, ok:bool, hint:string, route:?string}> */
    public function items(): array
    {
        $settings = Setting::all_values();
        $filled = fn (string $key) => filled($settings[$key] ?? null);
        $socials = collect(['social_instagram', 'social_facebook', 'social_pinterest', 'social_youtube', 'social_tiktok'])
            ->filter($filled);

        $draftLegal = Page::where('template', 'legal')
            ->where(fn ($q) => $q->where('body', 'like', '%draft%')->orWhere('body', 'like', '%placeholder%'))
            ->pluck('title');

        $activeAdmins = User::where('role', 'admin')->where('is_active', true)->count();
        $placeholderAccounts = User::where('email', 'like', '%@example.%')->count();

        return [
            $this->item('Business phone number', $filled('phone'), 'Shown in the header, footer and contact page.', 'admin.settings.edit'),
            $this->item('Business email address', $filled('email'), 'Public contact email.', 'admin.settings.edit'),
            $this->item('Address and city', $filled('address') && $filled('city'), 'Used for the contact page and local SEO.', 'admin.settings.edit'),
            $this->item('Real business name', $filled('site_name') && $settings['site_name'] !== self::DEFAULT_SITE_NAME,
                'The site name is still the default “'.self::DEFAULT_SITE_NAME.'”.', 'admin.settings.edit'),
            $this->item('Social media links', $socials->isNotEmpty(), 'Add at least one profile (Instagram, Facebook, Pinterest…).', 'admin.settings.edit'),
            $this->item('Lead notification email', $filled('notification_email'), 'Where new inquiries are emailed.', 'admin.settings.edit'),
            $this->item('Default SEO description', $filled('seo_default_description'), 'Fallback meta description for pages without one.', 'admin.settings.edit'),
            $this->item('Approved testimonials', Testimonial::approved()->exists(),
                'The testimonials section stays hidden until a genuine, approved review exists.', 'admin.testimonials.index'),
            $this->item('Legal pages reviewed', $draftLegal->isEmpty(),
                $draftLegal->isEmpty() ? 'No draft notes found.' : 'Still contain the draft note: '.$draftLegal->implode(', ').'.', 'admin.pages.index'),
            $this->item('Administrator accounts', $activeAdmins > 0 && $placeholderAccounts === 0,
                "{$activeAdmins} active admin(s)".($placeholderAccounts ? ", {$placeholderAccounts} account(s) still use a placeholder @example email" : '').'. Use real emails and strong passwords.',
                'admin.users.index'),
        ];
    }

    private function item(string $label, bool $ok, string $hint, ?string $route): array
    {
        return compact('label', 'ok', 'hint', 'route');
    }
}
