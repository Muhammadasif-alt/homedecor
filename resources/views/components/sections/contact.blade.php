@props(['section', 'data' => [], 'first' => false])

@php
    $site = \App\Models\Setting::all_values();
    $whatsapp = preg_replace('/\D/', '', $site['whatsapp'] ?? '');
    $details = array_filter([
        'phone' => ! empty($site['phone']) ? ['Phone', $site['phone'], 'tel:'.preg_replace('/[^0-9+]/', '', $site['phone'])] : null,
        'whatsapp' => $whatsapp ? ['WhatsApp', 'Message us', 'https://wa.me/'.$whatsapp] : null,
        'mail' => ! empty($site['email']) ? ['Email', $site['email'], 'mailto:'.$site['email']] : null,
        'map-pin' => ! empty($site['address']) ? ['Studio', $site['address'], null] : null,
        'clock' => ! empty($site['business_hours']) ? ['Hours', $site['business_hours'], null] : null,
    ]);
@endphp

<x-section :tone="$section->get('tone', 'ivory')">
    <div class="grid gap-14 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" align="left">{{ strip_tags($section->body) }}</x-section-heading>

            @if ($details)
                <ul class="mt-10 space-y-6">
                    @foreach ($details as $icon => [$label, $value, $href])
                        <li class="flex gap-5" data-reveal>
                            <span class="grid size-12 shrink-0 place-items-center rounded-full bg-white text-walnut"><x-icon :name="$icon" size="20" /></span>
                            <span>
                                <span class="eyebrow block">{{ $label }}</span>
                                @if ($href)
                                    <a href="{{ $href }}" class="mt-1 block text-lg hover:text-walnut" @if (str_starts_with($href, 'http')) target="_blank" rel="noopener" @endif>{{ $value }}</a>
                                @else
                                    <span class="mt-1 block whitespace-pre-line text-lg">{{ $value }}</span>
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif

            @php $socials = collect(['instagram', 'facebook', 'pinterest', 'youtube', 'tiktok'])->filter(fn ($n) => ! empty($site['social_'.$n])); @endphp
            @if ($socials->isNotEmpty())
                <ul class="mt-10 flex gap-3" aria-label="Social media">
                    @foreach ($socials as $network)
                        <li><a href="{{ $site['social_'.$network] }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}" class="grid size-11 place-items-center rounded-full border border-charcoal/15 hover:bg-charcoal hover:text-ivory"><x-icon :name="$network" size="18" /></a></li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="lg:col-span-7">
            <form method="POST" action="{{ route('inquiries.contact') }}" class="relative space-y-6 rounded-sm bg-white p-6 sm:p-10" data-reveal>
                @csrf
                <x-form.spam-guard />
                <div class="grid gap-6 sm:grid-cols-2">
                    <x-form.field name="name" label="Full name" autocomplete="name" required />
                    <x-form.field name="email" label="Email" type="email" autocomplete="email" required />
                    <x-form.field name="phone" label="Phone" type="tel" autocomplete="tel" />
                    <x-form.field name="subject" label="Subject" />
                </div>
                <x-form.field name="message" label="How can we help?" type="textarea" required />
                <x-form.consent />
                <x-button icon="arrow-right">Send message</x-button>
            </form>
        </div>
    </div>

    @if (! empty($site['map_embed_url']) && str_starts_with($site['map_embed_url'], 'https://www.google.com/maps/embed'))
        <div class="mt-16 aspect-[16/7] overflow-hidden rounded-sm" data-reveal>
            <iframe src="{{ $site['map_embed_url'] }}" class="h-full w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Map showing our studio location"></iframe>
        </div>
    @endif
</x-section>
