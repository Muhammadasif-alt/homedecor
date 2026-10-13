@use('App\Http\Controllers\Admin\Support\SectionSchema')
@php
    $typeLabel = \App\Models\PageSection::TYPES[$section->type] ?? $section->type;
@endphp

<x-admin.layout title="Edit section">
    <x-admin.page-header :title="$typeLabel" :description="'Section on “'.$page->title.'”'" :back="route('admin.pages.edit', $page)" />

    <x-admin.form :action="route('admin.pages.sections.update', [$page, $section])" method="PUT">
        <x-admin.form-grid>
            <x-admin.card title="Content">
                <div class="grid gap-5">
                    <x-admin.input name="eyebrow" label="Eyebrow" :value="$section->eyebrow" hint="Small label above the heading (optional)." />
                    <x-admin.input name="heading" label="Heading" :value="$section->heading" />
                    @if (in_array($section->type, SectionSchema::RICH_BODY_TYPES, true))
                        <x-admin.rich-text name="body" label="Body" :value="$section->body" />
                    @else
                        <x-admin.textarea name="body" label="Body" :value="$section->body" rows="4" hint="Short introductory text. Basic HTML is allowed and sanitised." />
                    @endif
                </div>
            </x-admin.card>

            @if ($fields)
                <x-admin.card title="Section settings">
                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ($fields as $key => $field)
                            @php
                                $name = "data[{$key}]";
                                $value = SectionSchema::formValue($field, $section->get($key));
                                $full = in_array($field['type'], ['items', 'gallery', 'lines', 'link'], true);
                            @endphp
                            <div @class(['sm:col-span-2' => $full])>
                                @switch($field['type'])
                                    @case('int')
                                        <x-admin.input :name="$name" type="number" min="1" max="200" :label="$field['label']" :value="$value" :hint="$field['hint'] ?? null" />
                                        @break
                                    @case('bool')
                                        <x-admin.checkbox :name="$name" :label="$field['label']" :checked="(bool) $value" :hint="$field['hint'] ?? null" />
                                        @break
                                    @case('select')
                                        <x-admin.select :name="$name" :label="$field['label']" :options="$field['options']" :value="$value" placeholder="Default" :hint="$field['hint'] ?? null" />
                                        @break
                                    @case('lines')
                                        <x-admin.textarea :name="$name" :label="$field['label']" :value="$value" rows="5" :hint="$field['hint'] ?? null" />
                                        @break
                                    @case('link')
                                        <fieldset class="grid gap-3 sm:grid-cols-2">
                                            <legend class="mb-1.5 text-sm font-medium">{{ $field['label'] }}</legend>
                                            <x-admin.input :name="$name.'[label]'" label="Label" :value="$value['label'] ?? null" />
                                            <x-admin.input :name="$name.'[url]'" label="Link" :value="$value['url'] ?? null" placeholder="/book-consultation" />
                                        </fieldset>
                                        @break
                                    @case('gallery')
                                        <x-admin.media-gallery :name="$name" :label="$field['label']" :items="(array) $value" :hint="$field['hint'] ?? null" />
                                        @break
                                    @case('items')
                                        <x-admin.repeater :name="$name" :label="$field['label']" :items="(array) $value" :fields="$field['fields']"
                                                          :add-label="$field['add'] ?? 'Add item'" :item-label="$field['item'] ?? 'Item'" :hint="$field['hint'] ?? null" />
                                        @break
                                    @default
                                        <x-admin.input :name="$name" :label="$field['label']" :value="$value" :hint="$field['hint'] ?? null" />
                                @endswitch
                            </div>
                        @endforeach
                    </div>
                </x-admin.card>
            @endif

            <x-admin.card title="Advanced data" description="Extra JSON keys for this section that have no form field above. Leave as {} if unsure.">
                <div x-data="{ open: {{ $extra || $errors->has('data_json') ? 'true' : 'false' }} }">
                    <button type="button" class="text-sm font-semibold text-walnut hover:underline" x-on:click="open = ! open" x-text="open ? 'Hide JSON' : 'Show JSON'"></button>
                    <div x-show="open" x-collapse class="pt-3">
                        <x-admin.textarea name="data_json" rows="6" class="font-mono"
                                          :value="$extra ? json_encode($extra, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '{}'" />
                    </div>
                </div>
            </x-admin.card>

            <x-slot:aside>
                <x-admin.card title="Visibility">
                    <x-admin.checkbox name="is_enabled" label="Show on page" :checked="$section->is_enabled" />
                </x-admin.card>
                @if (in_array($section->type, SectionSchema::MEDIA_TYPES, true))
                    <x-admin.card title="Image">
                        <x-admin.media-picker name="media_id" :value="$section->media_id" />
                    </x-admin.card>
                @endif
                <x-admin.notice>
                    <p><strong>{{ $typeLabel }}</strong></p>
                    <p>Sections that depend on real data (stats, testimonials, team, before &amp; after) hide themselves automatically when empty.</p>
                </x-admin.notice>
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.pages.edit', $page)" label="Save section" />
    </x-admin.form>
</x-admin.layout>
