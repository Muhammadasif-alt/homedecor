@props(['section', 'data' => [], 'first' => false])

@php
    use App\Models\Inquiry;
    $serviceOptions = $data['services']->pluck('name', 'id');
    $preselected = $data['services']->firstWhere('slug', request('service'))?->id;
@endphp

<x-section :tone="$section->get('tone', 'ivory')" id="consultation">
    <div class="grid gap-14 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <div class="lg:sticky lg:top-28">
                <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" align="left">{{ strip_tags($section->body) }}</x-section-heading>
                <ul class="mt-10 space-y-4 text-charcoal">
                    @foreach (['Tell us about your space and style', 'We reply to arrange a consultation', 'Receive a tailored proposal'] as $step)
                        <li class="flex items-center gap-4">
                            <span class="grid size-9 shrink-0 place-items-center rounded-full border border-walnut font-serif text-lg text-walnut">{{ $loop->iteration }}</span>
                            {{ $step }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <form method="POST" action="{{ route('inquiries.consultation') }}" enctype="multipart/form-data"
              class="relative space-y-10 rounded-sm bg-white p-6 sm:p-10 lg:col-span-8">
            @csrf
            <x-form.spam-guard />

            <fieldset class="space-y-6">
                <legend class="mb-2 font-serif text-2xl">Your details</legend>
                <div class="grid gap-6 sm:grid-cols-2">
                    <x-form.field name="name" label="Full name" autocomplete="name" required />
                    <x-form.field name="email" label="Email" type="email" autocomplete="email" required />
                    <x-form.field name="phone" label="Phone number" type="tel" autocomplete="tel" required />
                    <x-form.field name="preferred_contact" label="Preferred contact method" type="select" :options="Inquiry::CONTACT_METHODS" placeholder="Choose one" required />
                </div>
            </fieldset>

            <fieldset class="space-y-6">
                <legend class="mb-2 font-serif text-2xl">Your project</legend>
                <div class="grid gap-6 sm:grid-cols-2">
                    <x-form.field name="service_id" label="Service required" type="select" :options="$serviceOptions" :value="$preselected" placeholder="Not sure yet" />
                    <x-form.field name="property_type" label="Property type" type="select" :options="Inquiry::PROPERTY_TYPES" placeholder="Choose one" />
                    <x-form.field name="location" label="Project location" autocomplete="address-level2" placeholder="Area or city" />
                    <x-form.field name="budget" label="Approximate budget" type="select" :options="Inquiry::BUDGETS" placeholder="Choose one" />
                    <x-form.field name="preferred_date" label="Preferred consultation date" type="date" :min="now()->toDateString()" />
                    <x-form.field name="attachments[]" label="Photos of your space (optional)" type="file" accept="image/jpeg,image/png,image/webp" multiple hint="Up to 5 images, 8 MB each." />
                </div>
                <x-form.field name="message" label="Tell us about your project" type="textarea" placeholder="Rooms, style you love, timing, anything we should know…" required />
            </fieldset>

            <x-form.consent />
            <x-button size="lg" icon="arrow-right">Request my consultation</x-button>
        </form>
    </div>
</x-section>
