@php
    $user = auth()->user();
    $newLeads = \App\Models\Inquiry::where('status', 'new')->count();

    $groups = [
        'Overview' => [
            ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'home'],
            ['Leads', 'admin.inquiries.index', 'admin.inquiries.*', 'inbox', $newLeads],
        ],
        'Content' => [
            ['Pages', 'admin.pages.index', 'admin.pages.*', 'document'],
            ['Services', 'admin.services.index', 'admin.services.*', 'sparkles'],
            ['Portfolio', 'admin.projects.index', 'admin.projects.*', 'briefcase'],
            ['Project categories', 'admin.project-categories.index', 'admin.project-categories.*', 'tag'],
            ['Blog posts', 'admin.blog-posts.index', 'admin.blog-posts.*', 'newspaper'],
            ['Blog categories', 'admin.blog-categories.index', 'admin.blog-categories.*', 'tag'],
            ['Testimonials', 'admin.testimonials.index', 'admin.testimonials.*', 'chat'],
            ['FAQs', 'admin.faqs.index', 'admin.faqs.*', 'question'],
            ['Service areas', 'admin.service-areas.index', 'admin.service-areas.*', 'map'],
            ['Media library', 'admin.media.index', 'admin.media.*', 'photo'],
        ],
        'Site' => array_values(array_filter([
            ['Navigation', 'admin.navigation.index', 'admin.navigation.*', 'bars'],
            $user->isAdmin() ? ['Settings', 'admin.settings.edit', 'admin.settings.*', 'cog'] : null,
            $user->isAdmin() ? ['Redirects', 'admin.redirects.index', 'admin.redirects.*', 'arrows'] : null,
            $user->isAdmin() ? ['Users', 'admin.users.index', 'admin.users.*', 'users'] : null,
        ])),
    ];
@endphp

<aside class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-charcoal text-ivory transition-transform duration-300 ease-soft lg:translate-x-0"
       x-bind:class="nav && 'translate-x-0'" aria-label="Admin navigation">
    <div class="flex h-16 items-center justify-between border-b border-white/10 px-6">
        <a href="{{ route('admin.dashboard') }}" class="font-serif text-xl tracking-wide text-ivory">
            {{ config('app.name', 'Home Decor Studio') }}
        </a>
        <button type="button" class="rounded p-1 text-sand hover:text-ivory lg:hidden" x-on:click="nav = false" aria-label="Close navigation">
            <x-admin.icon name="x" />
        </button>
    </div>

    <nav class="flex-1 space-y-7 overflow-y-auto px-3 py-6">
        @foreach ($groups as $label => $links)
            <div>
                <p class="px-3 pb-2 text-[0.68rem] font-semibold uppercase tracking-[0.22em] text-sand/70">{{ $label }}</p>
                <ul class="space-y-0.5">
                    @foreach ($links as $link)
                        <li>
                            <x-admin.nav-link :href="route($link[1])" :active="request()->routeIs($link[2])" :icon="$link[3]" :badge="$link[4] ?? null">
                                {{ $link[0] }}
                            </x-admin.nav-link>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-white/10 px-6 py-4 text-xs text-sand/80">
        Signed in as <span class="font-semibold text-ivory">{{ $user->name }}</span>
        <span class="ml-1 rounded bg-white/10 px-1.5 py-0.5 uppercase tracking-wider">{{ $user->role }}</span>
    </div>
</aside>
