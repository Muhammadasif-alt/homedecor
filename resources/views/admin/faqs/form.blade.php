@php $editing = $faq->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit FAQ' : 'Add FAQ'">
    <x-admin.page-header :title="$editing ? 'Edit FAQ' : 'Add FAQ'" :back="route('admin.faqs.index')" />

    <x-admin.form :action="$editing ? route('admin.faqs.update', $faq) : route('admin.faqs.store')" :method="$editing ? 'PUT' : 'POST'">
        <x-admin.form-grid>
            <x-admin.card title="Question & answer">
                <div class="grid gap-5">
                    <x-admin.input name="question" label="Question" :value="$faq->question" required />
                    <x-admin.textarea name="answer" label="Answer" :value="$faq->answer" rows="6" required hint="Plain text; simple links are allowed." />
                </div>
            </x-admin.card>

            <x-slot:aside>
                <x-admin.card title="Placement">
                    <div class="space-y-4">
                        <x-admin.select name="service_id" label="Service" :options="$serviceOptions" :value="$faq->service_id" placeholder="— General question —"
                                        hint="Service questions appear on that service’s page." />
                        <x-admin.select name="category" label="Category" :options="\App\Models\Faq::CATEGORIES" :value="$faq->category" />
                        <x-admin.checkbox name="show_on_home" label="Show on home page" :checked="$faq->show_on_home" />
                        <x-admin.checkbox name="is_published" label="Published" :checked="$faq->is_published" />
                        <x-admin.input name="sort_order" type="number" min="0" label="Display order" :value="$faq->sort_order" />
                    </div>
                </x-admin.card>
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.faqs.index')" />
    </x-admin.form>
</x-admin.layout>
