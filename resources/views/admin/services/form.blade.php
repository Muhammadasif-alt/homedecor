@php
    $editing = $service->exists;
    $sectionRows = old('sections', $editing
        ? $service->sections->map->only(['id', 'heading', 'body', 'media_id'])->all()
        : []);
    $gallery = $editing ? $service->gallery->pluck('id')->all() : [];
@endphp

<x-admin.layout :title="$editing ? 'Edit service' : 'New service'">
    <x-admin.page-header :title="$editing ? $service->name : 'New service'" :back="route('admin.services.index')">
        @if ($editing && $service->is_published && Route::has('services.show'))
            <x-slot:actions>
                <x-admin.button variant="secondary" icon="external" :href="route('services.show', $service)" target="_blank" rel="noopener">View</x-admin.button>
            </x-slot:actions>
        @endif
    </x-admin.page-header>

    <x-admin.form :action="$editing ? route('admin.services.update', $service) : route('admin.services.store')" :method="$editing ? 'PUT' : 'POST'">
        <x-admin.form-grid>
            <x-admin.card title="Basics">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.input name="name" label="Service name" :value="$service->name" required />
                    <x-admin.input name="slug" label="Slug" :value="$service->slug" hint="Used in the URL. Generated from the name when empty." />
                    <x-admin.textarea name="short_description" label="Short description" :value="$service->short_description" rows="2" :counter="500" required
                                      hint="Shown on service cards." class="sm:col-span-2" />
                    <x-admin.input name="icon" label="Icon name" :value="$service->icon" hint="Optional icon key, e.g. sofa, lamp, curtains." />
                </div>
            </x-admin.card>

            <x-admin.card title="Hero">
                <div class="grid gap-5">
                    <x-admin.input name="hero_title" label="Hero heading" :value="$service->hero_title" required />
                    <x-admin.textarea name="hero_description" label="Hero text" :value="$service->hero_description" rows="3" required />
                </div>
            </x-admin.card>

            <x-admin.card title="Introduction">
                <x-admin.rich-text name="intro" :value="$service->intro" required />
            </x-admin.card>

            <x-admin.card title="Lists" description="Short title + text pairs shown as cards on the service page. Empty lists are hidden.">
                <div class="space-y-8" x-data="{ tab: 'included' }">
                    <div class="flex flex-wrap gap-2" role="tablist">
                        @foreach (\App\Http\Requests\Admin\ServiceRequest::LISTS as $list => $listLabel)
                            <button type="button" role="tab" x-on:click="tab = '{{ $list }}'" x-bind:aria-selected="tab === '{{ $list }}'"
                                    class="rounded-full border px-3 py-1 text-xs font-semibold"
                                    x-bind:class="tab === '{{ $list }}' ? 'border-charcoal bg-charcoal text-ivory' : 'border-charcoal/15 bg-white hover:border-walnut'">
                                {{ $listLabel }}
                                @if ($errors->has($list.'.*'))<span class="text-red-600">•</span>@endif
                            </button>
                        @endforeach
                    </div>
                    @foreach (\App\Http\Requests\Admin\ServiceRequest::LISTS as $list => $listLabel)
                        <div x-show="tab === '{{ $list }}'" role="tabpanel">
                            <x-admin.repeater :name="$list" :label="$listLabel" :items="$service->{$list} ?? []"
                                              :fields="['title' => 'Title', 'text' => ['label' => 'Text', 'type' => 'textarea']]" />
                        </div>
                    @endforeach
                </div>
            </x-admin.card>

            <x-admin.card title="Content blocks" description="Optional long-form sections with a heading, text and image, shown in this order.">
                <div x-data="rowList({{ count($sectionRows) }})" class="space-y-3">
                    <ol x-ref="list" class="space-y-3">
                        @foreach ($sectionRows as $index => $row)
                            @include('admin.services._section-row', ['index' => $index, 'row' => $row])
                        @endforeach
                    </ol>
                    <template x-ref="template">
                        @include('admin.services._section-row', ['index' => '__INDEX__', 'row' => []])
                    </template>
                    <x-admin.button type="button" variant="secondary" size="sm" icon="plus" x-on:click="add()">Add content block</x-admin.button>
                </div>
            </x-admin.card>

            <x-admin.card title="Gallery">
                <x-admin.media-gallery name="gallery" :items="$gallery" hint="Images shown in the service page gallery, in this order." />
            </x-admin.card>

            <x-admin.card title="Connections">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.multi-select name="related" label="Related services" :options="$serviceOptions"
                                          :selected="$editing ? $service->related->pluck('id')->all() : []" />
                    <x-admin.multi-select name="projects" label="Linked portfolio projects" :options="$projectOptions"
                                          :selected="$editing ? $service->projects->pluck('id')->all() : []" empty="No projects yet." />
                </div>
            </x-admin.card>

            <x-admin.seo-fields :model="$service" />

            <x-slot:aside>
                <x-admin.card title="Publishing">
                    <div class="space-y-4">
                        <x-admin.checkbox name="is_published" label="Published" :checked="$service->is_published" />
                        <x-admin.checkbox name="is_featured" label="Featured" :checked="$service->is_featured" hint="Highlighted on the home page." />
                        <x-admin.input name="sort_order" type="number" min="0" label="Display order" :value="$service->sort_order" hint="Lower numbers appear first." />
                    </div>
                </x-admin.card>
                <x-admin.card title="Hero image">
                    <x-admin.media-picker name="hero_media_id" :value="$service->hero_media_id" />
                </x-admin.card>
                @if ($editing)
                    <x-admin.card title="Service FAQs">
                        <p class="text-sm text-charcoal-soft">{{ $faqCount }} question(s) linked to this service.</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <x-admin.button variant="secondary" size="sm" :href="route('admin.faqs.index', ['service' => $service->id])">Manage</x-admin.button>
                            <x-admin.button variant="secondary" size="sm" icon="plus" :href="route('admin.faqs.create', ['service_id' => $service->id])">Add FAQ</x-admin.button>
                        </div>
                    </x-admin.card>
                @endif
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.services.index')" :label="$editing ? 'Save service' : 'Create service'" />
    </x-admin.form>
</x-admin.layout>
