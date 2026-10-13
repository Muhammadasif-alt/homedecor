@props(['model' => null, 'canonical' => true, 'og' => true, 'robots' => false])

{{-- Shared SEO card: meta title (≤60), meta description (≤155), canonical, OG image, robots. --}}
<x-admin.card title="Search & social" description="How this page appears in Google results and when shared. Leave blank to use sensible defaults.">
    <div class="grid gap-5">
        <x-admin.input name="meta_title" label="Meta title" :value="$model?->meta_title" :counter="60" maxlength="120"
                       hint="Defaults to the title. Aim for 50–60 characters; the site name is appended automatically." />
        <x-admin.textarea name="meta_description" label="Meta description" :value="$model?->meta_description" :counter="155" rows="3" maxlength="320"
                          hint="A one or two sentence summary, ideally 120–155 characters." />
        @if ($canonical)
            <x-admin.input name="canonical_url" type="url" label="Canonical URL" :value="$model?->canonical_url" placeholder="https://…"
                           hint="Only set this when the same content lives at another URL." />
        @endif
        @if ($og)
            <x-admin.media-picker name="og_media_id" label="Social share image" :value="$model?->og_media_id"
                                  hint="Shown when the link is shared on social media. Defaults to the main image." />
        @endif
        @if ($robots)
            <x-admin.checkbox name="robots_index" label="Allow search engines to index this page"
                              :checked="$model?->robots_index ?? true" hint="Untick for thin or private pages (adds noindex)." />
        @endif
    </div>
</x-admin.card>
