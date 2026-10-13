{{-- Real team members entered in the CMS only; skipped when empty. --}}
@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'white')">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>

    <ul class="mt-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($data['items'] as $member)
            @php $photo = $data['media']->get($member['media_id'] ?? null); @endphp
            <li class="text-center" data-reveal>
                <div class="mx-auto aspect-[4/5] overflow-hidden rounded-sm bg-sand-light">
                    @if ($photo)<x-picture :media="$photo" sizes="(min-width: 1024px) 25vw, 50vw" class="block h-full" />@endif
                </div>
                <h3 class="mt-5 font-serif text-2xl">{{ $member['name'] }}</h3>
                @if (! empty($member['role']))<p class="eyebrow mt-1">{{ $member['role'] }}</p>@endif
                @if (! empty($member['bio']))<p class="mt-3 text-sm leading-relaxed text-charcoal-soft">{{ $member['bio'] }}</p>@endif
            </li>
        @endforeach
    </ul>
</x-section>
