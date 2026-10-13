<x-admin.layout title="Settings">
    <x-admin.page-header title="Settings" description="Business details used across the website. Empty fields are simply hidden on the site." />

    <x-admin.form :action="route('admin.settings.update')" method="PUT">
        @foreach ($groups as $group => $fields)
            <x-admin.card :title="$group">
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach ($fields as $key => $field)
                        @php
                            $type = $field['type'] ?? 'text';
                            $value = $values[$key] ?? null;
                        @endphp
                        @switch($type)
                            @case('textarea')
                                <x-admin.textarea :name="$key" :label="$field['label']" :value="$value" rows="3" :counter="$field['counter'] ?? null"
                                                  :hint="$field['hint'] ?? null" class="sm:col-span-2" />
                                @break
                            @case('bool')
                                <x-admin.checkbox :name="$key" :label="$field['label']" :checked="(bool) $value" :hint="$field['hint'] ?? null" class="sm:col-span-2" />
                                @break
                            @case('media')
                                <x-admin.media-picker :name="$key" :label="$field['label']" :value="$value" :hint="$field['hint'] ?? null" class="sm:col-span-2" />
                                @break
                            @default
                                <x-admin.input :name="$key" :type="$type" :label="$field['label']" :value="$value" :hint="$field['hint'] ?? null"
                                               :required="$field['required'] ?? false" :class="$key === 'map_embed_url' ? 'sm:col-span-2' : ''" />
                        @endswitch
                    @endforeach
                </div>
            </x-admin.card>
        @endforeach

        <x-admin.card title="Process steps" description="Shown by every “Process steps” section and on the Our Process page.">
            <x-admin.repeater name="process_steps" :items="$values['process_steps'] ?? []" add-label="Add step" item-label="Step"
                              :fields="[
                                  'title' => 'Title',
                                  'icon' => ['label' => 'Icon name', 'placeholder' => 'e.g. chat'],
                                  'text' => ['label' => 'Description', 'type' => 'textarea'],
                                  'media_id' => ['label' => 'Image (optional)', 'type' => 'media'],
                              ]" />
        </x-admin.card>

        <x-admin.form-actions label="Save settings" />
    </x-admin.form>
</x-admin.layout>
