@php $editing = $page->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit page' : 'New page'">
    <x-admin.page-header :title="$editing ? $page->title : 'New page'" :back="route('admin.pages.index')"
                         :description="$editing ? 'URL: /'.($page->slug === 'home' ? '' : $page->slug) : null">
        @if ($editing && $page->is_published)
            <x-slot:actions>
                <x-admin.button variant="secondary" icon="external" :href="url($page->slug === 'home' ? '/' : $page->slug)" target="_blank" rel="noopener">View</x-admin.button>
            </x-slot:actions>
        @endif
    </x-admin.page-header>

    <x-admin.form :action="$editing ? route('admin.pages.update', $page) : route('admin.pages.store')" :method="$editing ? 'PUT' : 'POST'">
        <x-admin.form-grid>
            <x-admin.card title="Page details">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.input name="title" label="Title" :value="$page->title" required class="sm:col-span-2" />
                    <x-admin.input name="slug" label="Slug" :value="$page->slug" hint="Lowercase words separated by hyphens. Generated from the title when empty." />
                    <x-admin.select name="template" label="Template" :options="\App\Http\Requests\Admin\PageRequest::TEMPLATES + [$page->template => ucfirst($page->template)]" :value="$page->template" />
                </div>
            </x-admin.card>

            @if ($page->template === 'legal' || ! $editing)
                <x-admin.card title="Page text" description="Used by legal / simple text pages. Section-based pages ignore this.">
                    <x-admin.rich-text name="body" :value="$page->body" />
                </x-admin.card>
            @endif

            <x-admin.seo-fields :model="$page" :robots="true" />

            <x-slot:aside>
                <x-admin.card title="Visibility">
                    <x-admin.checkbox name="is_published" label="Published" :checked="$page->is_published" hint="Unpublished pages return 404 on the site." />
                </x-admin.card>
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.pages.index')" :label="$editing ? 'Save page' : 'Create page'" />
    </x-admin.form>

    @if ($editing)
        <x-admin.card title="Sections" description="Content blocks shown top to bottom. Hidden sections are kept but not displayed." :padding="false">
            <ol class="divide-y divide-charcoal/5">
                @forelse ($page->sections as $section)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3 sm:px-6">
                        <span class="w-6 text-sm text-charcoal-soft">{{ $loop->iteration }}</span>
                        <x-admin.thumb :media="$section->media" size="size-10" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold">{{ $section->heading ?: (\App\Models\PageSection::TYPES[$section->type] ?? $section->type) }}</p>
                            <p class="text-xs text-charcoal-soft">{{ \App\Models\PageSection::TYPES[$section->type] ?? $section->type }}</p>
                        </div>
                        <x-admin.status-badge :status="$section->is_enabled ? 'enabled' : 'hidden'" :label="$section->is_enabled ? 'Shown' : 'Hidden'" />
                        <div class="flex items-center gap-1.5">
                            @foreach (['up' => 'chevron-up', 'down' => 'chevron-down'] as $direction => $icon)
                                <form method="POST" action="{{ route('admin.pages.sections.move', [$page, $section]) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="direction" value="{{ $direction }}">
                                    <button type="submit" title="Move {{ $direction }}" @disabled(($direction === 'up' && $loop->parent->first) || ($direction === 'down' && $loop->parent->last))
                                            class="rounded border border-charcoal/15 bg-white p-1.5 text-charcoal-soft hover:border-walnut hover:text-walnut disabled:opacity-30">
                                        <x-admin.icon :name="$icon" class="size-4" /><span class="sr-only">Move {{ $direction }}</span>
                                    </button>
                                </form>
                            @endforeach
                            <form method="POST" action="{{ route('admin.pages.sections.toggle', [$page, $section]) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $section->is_enabled ? 'Hide' : 'Show' }}"
                                        class="rounded border border-charcoal/15 bg-white p-1.5 text-charcoal-soft hover:border-walnut hover:text-walnut">
                                    <x-admin.icon :name="$section->is_enabled ? 'eye-off' : 'eye'" class="size-4" />
                                    <span class="sr-only">{{ $section->is_enabled ? 'Hide' : 'Show' }} section</span>
                                </button>
                            </form>
                            <x-admin.icon-link :href="route('admin.pages.sections.edit', [$page, $section])" label="Edit section" />
                            <x-admin.delete-button :action="route('admin.pages.sections.destroy', [$page, $section])" label="Delete section"
                                                   confirm="Delete this section and its content?" />
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-sm text-charcoal-soft">No sections yet.</li>
                @endforelse
            </ol>
            <x-slot:footer>
                <form method="POST" action="{{ route('admin.pages.sections.store', $page) }}" class="flex w-full flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                    @csrf
                    <label for="new-section-type" class="text-sm font-medium">Add section</label>
                    <select id="new-section-type" name="type" required class="rounded border border-charcoal/15 bg-white px-3 py-2 text-sm focus:border-walnut focus:outline-none">
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-admin.button variant="accent" icon="plus">Add</x-admin.button>
                </form>
            </x-slot:footer>
        </x-admin.card>
    @endif
</x-admin.layout>
