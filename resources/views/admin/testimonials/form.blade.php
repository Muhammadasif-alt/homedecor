@php $editing = $testimonial->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit testimonial' : 'Add testimonial'">
    <x-admin.page-header :title="$editing ? 'Testimonial from '.$testimonial->customer_name : 'Add testimonial'" :back="route('admin.testimonials.index')" />

    @include('admin.testimonials._genuine-note')

    <x-admin.form :action="$editing ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store')" :method="$editing ? 'PUT' : 'POST'">
        <x-admin.form-grid>
            <x-admin.card title="Review">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.input name="customer_name" label="Customer name" :value="$testimonial->customer_name" required
                                   hint="As the customer agreed to be credited, e.g. “Sarah K.”" />
                    <x-admin.select name="rating" label="Rating (optional)" :options="[5 => '★★★★★ 5', 4 => '★★★★ 4', 3 => '★★★ 3', 2 => '★★ 2', 1 => '★ 1']"
                                    :value="$testimonial->rating" placeholder="— No rating —" />
                    <x-admin.textarea name="content" label="Review text" :value="$testimonial->content" rows="6" required class="sm:col-span-2"
                                      hint="The customer’s own words." />
                    <x-admin.select name="service_id" label="Service" :options="$serviceOptions" :value="$testimonial->service_id" placeholder="— None —" />
                    <x-admin.select name="project_id" label="Project" :options="$projectOptions" :value="$testimonial->project_id" placeholder="— None —" />
                </div>
            </x-admin.card>

            <x-slot:aside>
                <x-admin.card title="Publishing">
                    <div class="space-y-4">
                        <x-admin.checkbox name="is_approved" label="Approved — show on website" :checked="$testimonial->is_approved"
                                          hint="Tick only once the customer has given permission." />
                        <x-admin.input name="sort_order" type="number" min="0" label="Display order" :value="$testimonial->sort_order" />
                    </div>
                </x-admin.card>
                <x-admin.card title="Customer photo">
                    <x-admin.media-picker name="photo_media_id" :value="$testimonial->photo_media_id" hint="Optional, with permission." />
                </x-admin.card>
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.testimonials.index')" />
    </x-admin.form>
</x-admin.layout>
