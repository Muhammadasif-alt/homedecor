{{--
    Accessible FAQ accordion. <x-accordion :items="$faqs" /> — items need question + answer.
--}}
@props(['items'])

<div {{ $attributes->class(['divide-y divide-charcoal/12 border-y border-charcoal/12']) }} x-data="{ open: null }">
    @foreach ($items as $item)
        @php $id = 'faq-'.($item->id ?? $loop->index).'-'.uniqid(); @endphp
        <div>
            <h3 class="font-sans">
                <button type="button" id="{{ $id }}-btn" aria-controls="{{ $id }}"
                        @click="open = open === {{ $loop->index }} ? null : {{ $loop->index }}"
                        :aria-expanded="open === {{ $loop->index }}"
                        class="flex w-full items-center justify-between gap-6 py-6 text-left text-lg font-medium text-charcoal transition hover:text-walnut">
                    <span>{{ $item->question }}</span>
                    <span class="grid size-9 shrink-0 place-items-center rounded-full border border-charcoal/15 transition" :class="open === {{ $loop->index }} && 'bg-charcoal text-ivory rotate-45'">
                        <x-icon name="plus" size="18" />
                    </span>
                </button>
            </h3>
            <div id="{{ $id }}" role="region" aria-labelledby="{{ $id }}-btn" x-show="open === {{ $loop->index }}" x-collapse x-cloak>
                <div class="max-w-3xl pb-7 leading-relaxed text-charcoal-soft">{!! nl2br(e($item->answer)) !!}</div>
            </div>
        </div>
    @endforeach
</div>
